<?php
/**
 * Deletion page
 * @see AuthController::actionDeleted()
 *
 * @var string $userId
 */

use app\controllers\AuthController;
use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\helpers\Html;

?>
<h1 class="page-header">
    <?= Yii::t('app', 'Instagram Data Removed') ?>
</h1>
<div class="alert alert-success text-center">
    <p>
        <?= Yii::t('app', 'All Instagram data related to your account {id} has been successfully removed.', [
            'name' => Html::tag('strong', Html::encode($userId)),
        ]); ?>
    </p>
    <p>
        <?= Yii::t('app', 'You can now close this window.'); ?>
    </p>
</div>