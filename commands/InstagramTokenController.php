<?php

namespace app\commands;

use app\models\InstagramToken;
use davidhirtz\yii2\datetime\Date;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Yii;
use yii\console\Controller;

/**
 * InstagramTokenController takes care of refreshing the Instagram tokens on time.
 * @noinspection PhpUnused
 */
class InstagramTokenController extends Controller
{
    /**
     * Refreshes all Instagram tokens which expire in less than a month.
     * @noinspection PhpUnused
     */
    public function actionRefreshAll()
    {
        $query = InstagramToken::find()->where(['<', 'expires_at', new Date('1 month')]);

        /** @var InstagramToken $instagram */
        foreach ($query->each() as $instagram) {
            try {
                $instagram->refreshAccessToken()->update();
            } catch (Exception $exception) {
                if ($exception instanceof ClientException) {
                    $contents = json_decode($exception->getResponse()->getBody()->getContents(), true);

                    $message = Yii::$app->getMailer()->compose('@app/mail/error', [
                        'instagram' => $instagram,
                        'error' => $contents['error']['message'] ?? null,
                    ]);

                    $message->setSubject(Yii::t('app', "Instafeed Error – {name}", ['name' => $instagram->name]))
                        ->setFrom(Yii::$app->params['email'])
                        ->setTo(Yii::$app->params['reportEmail'] ?? Yii::$app->params['email'])
                        ->send();

                    Yii::error($contents);
                }

                Yii::error($exception);
            }
        }
    }
}