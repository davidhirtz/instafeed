<?php

namespace app\controllers;

use app\models\InstagramToken;
use Exception;
use GuzzleHttp\Client;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ApiController implements the API endpoint for websites.
 * @property Response $response
 */
class ApiController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @param $action
     * @return bool
     */
    public function beforeAction($action)
    {
        Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', '*');
        $this->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * @param string $slug
     * @return Response
     */
    public function actionIndex($slug)
    {
        $instagram = InstagramToken::findOne(['slug' => $slug]);

        if (!$instagram || !$instagram->access_token) {
            throw new NotFoundHttpException();
        }

        return Yii::$app->getCache()->getOrSet($instagram->getCacheKey(), function () use ($instagram) {
            return $this->getMediaForAccessToken($instagram->access_token);
        }, $instagram->cache_duration);
    }

    /**
     * @param string $accessToken
     * @return array
     */
    private function getMediaForAccessToken($accessToken)
    {
        try {
            $client = new Client();
            $response = $client->get('https://graph.instagram.com/me/media', [
                'query' => [
                    'access_token' => $accessToken,
                    'fields' => 'id,caption,media_url,permalink,media_type',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            unset($data['paging']['next']);
            return $data;
        } catch (Exception $exception) {
            Yii::error($exception);
        }

        return [];
    }
}