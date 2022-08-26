<?php

namespace app\controllers;

use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\web\Controller;
use Exception;
use GuzzleHttp\Client;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * AuthController implements the handshake with Instagram/Facebook and stores the token.
 */
class AuthController extends Controller
{
    /**
     * @return string
     */
    public $layout = '@skeleton/modules/admin/views/layouts/main';

    /**
     * @inheritDoc
     */
    public function init()
    {
        Yii::$app->getRequest()->enableCsrfValidation = false;
        parent::init();
    }

    /**
     * @param string $state
     * @return string|Response
     */
    public function actionLogin($state)
    {
        $instagram = $this->getInstagramTokenFromState($state);

        if ($instagram->access_token) {
            throw new ForbiddenHttpException(Yii::t('app', 'This Instagram account is already connected to a user.'));
        }

        /** @see AuthController::actionAuthorize() */
        return $this->redirect('https://api.instagram.com/oauth/authorize?' . http_build_query([
                'client_id' => Yii::$app->params['instagramAppId'],
                'redirect_uri' => $this->getRedirectUri(),
                'scope' => 'user_profile,user_media,instagram_graph_user_media',
                'response_type' => 'code',
                'state' => $state,
            ]));
    }

    /**
     * @param string $state
     * @return string|Response
     */
    public function actionAuthorize($state)
    {
        $instagram = $this->getInstagramTokenFromState($state);

        if ($instagram->access_token) {
            throw new ForbiddenHttpException(Yii::t('app', 'This Instagram account is already connected to a user.'));
        }

        $response = Yii::$app->getRequest();
        $client = new Client();

        if (!($code = $response->get('code'))) {
            throw new ForbiddenHttpException($response->get('error_description'));
        }

        $response = $client->post('https://api.instagram.com/oauth/access_token', [
            'form_params' => [
                'client_id' => Yii::$app->params['instagramAppId'],
                'client_secret' => Yii::$app->params['instagramAppSecret'],
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->getRedirectUri(),
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['access_token'])) {
            throw new ForbiddenHttpException($data['error_message'] ?? null);
        }

        $response = $client->get('https://graph.instagram.com/access_token?' . http_build_query([
                'grant_type' => 'ig_exchange_token',
                'client_secret' => Yii::$app->params['instagramAppSecret'],
                'access_token' => $data['access_token'],
            ]));

        $instagram->setAttributesFromApiResponse($response);

        $response = $client->get('https://graph.instagram.com/me?' . http_build_query([
                'fields' => 'id,username',
                'access_token' => $data['access_token'],
            ]));

        $data = json_decode($response->getBody()->getContents(), true);
        $instagram->username = $data['username'] ?? null;

        if (!$instagram->save()) {
            $instagram->logErrors();
            throw new ServerErrorHttpException();
        }

        return $this->render('completed', [
            'instagram' => $instagram,
        ]);
    }

    /**
     * @param string $state
     * @return InstagramToken
     */
    private function getInstagramTokenFromState($state)
    {
        try {
            list($id, $verificationToken) = unserialize(base64_decode(urldecode($state)));
        } catch (Exception $exception) {
            throw new NotFoundHttpException();
        }

        $instagram = InstagramToken::findOne(['id' => $id, 'verification_token' => $verificationToken]);

        if (!$instagram) {
            throw new NotFoundHttpException();
        }

        return $instagram;
    }

    /**
     * @return string
     */
    private function getRedirectUri()
    {
        return Yii::$app->getUrlManager()->createAbsoluteUrl(['/auth/authorize']);
    }
}