<?php

namespace app\commands;

use app\models\InstagramToken;
use davidhirtz\yii2\datetime\Date;
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
            $instagram->refreshAccessToken()
                ->update();
        }
    }
}