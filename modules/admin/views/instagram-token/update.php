<?php
/**
 * @see \app\modules\admin\controllers\InstagramTokenController::actionUpdate()
 *
 * @var \davidhirtz\yii2\skeleton\web\View $this
 * @var InstagramToken $instagram
 */

use app\models\InstagramToken;
use app\modules\admin\controllers\InstagramTokenController;
use app\modules\admin\widgets\forms\InstagramTokenActiveForm;
use app\modules\admin\widgets\panels\InstagramTokenHelpPanel;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\widgets\bootstrap\Panel;
use davidhirtz\yii2\skeleton\widgets\forms\DeleteActiveForm;
use yii\helpers\Url;

$this->setTitle(Yii::t('app', 'Update Account'));
$this->setBreadcrumb(Yii::t('app', 'Accounts'), ['index']);
?>

<h1 class="page-header">
    <a href="<?= Url::toRoute(['index']) ?>"><?= Yii::t('app', 'Instagram Accounts') ?></a>
</h1>

<?= Html::errorSummary($instagram); ?>

<?= Panel::widget([
    'title' => $this->title,
    'content' => InstagramTokenActiveForm::widget([
        'model' => $instagram,
    ]),
]); ?>

<?= InstagramTokenHelpPanel::widget([
        'model' => $instagram,
]); ?>

<?= Panel::widget([
    'type' => 'danger',
    'title' => Yii::t('app', 'Delete account'),
    /** @see InstagramTokenController::actionDelete() */
    'content' => DeleteActiveForm::widget([
        'model' => $instagram,
    ]),
]); ?>
