<?php

namespace app\controllers;

use app\models\InstagramToken;
use GuzzleHttp\Client;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ApiController implements the API endpoint for websites.
 */
class ApiController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @var string
     */
    public $format = Response::FORMAT_JSON;

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
        $client = new Client();
        $response = $client->get('https://graph.instagram.com/me/media?fields=media_url,permalink', [
            'query' => [
                'access_token' => $accessToken,
                'fields' => 'id,caption,media_url,permalink,media_type',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        unset($data['paging']['next']);

        return $data;
    }
}