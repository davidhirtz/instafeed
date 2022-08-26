<?php
/**
 * @see \app\modules\admin\controllers\InstagramTokenController::actionCreate()
 *
 * @var \davidhirtz\yii2\skeleton\web\View $this
 * @var InstagramToken $instagram
 */

use app\models\InstagramToken;
use app\modules\admin\widgets\forms\InstagramTokenActiveForm;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\widgets\bootstrap\Panel;
use yii\helpers\Url;

$this->setTitle(Yii::t('app', 'New Instagram Account'));
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
