<?php
/**
 * Success page
 * @see AuthController::actionAuthorize()
 *
 * @var InstagramToken $instagram
 *
 */

use app\controllers\AuthController;
use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\helpers\Html;

?>
<h1 class="page-header">
    <?= Yii::t('app', 'Instagram Connected') ?>
</h1>
<div class="alert alert-success text-center">
    <p>
        <?= Yii::t('app', 'Your Instagram account {name} has been successfully connected.', [
            'name' => Html::tag('strong', Html::encode($instagram->username)),
        ]); ?>
    </p>
    <p>
        <?= Yii::t('app', 'You can now close this window.'); ?>
    </p>
</div>