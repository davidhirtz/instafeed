<?php
/**
 * @see \app\modules\admin\controllers\InstagramTokenController::actionIndex()
 *
 * @var View $this
 * @var ActiveDataProvider $provider
 */

use app\modules\admin\widgets\grids\InstagramTokenGridView;
use davidhirtz\yii2\skeleton\web\View;
use davidhirtz\yii2\skeleton\widgets\bootstrap\Panel;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

$this->setTitle(Yii::t('app', 'Instagram Accounts'));
$this->setBreadcrumb(Yii::t('app', 'Accounts'), ['index']);
?>
    <h1 class="page-header">
        <a href="<?= Url::toRoute(['index']) ?>"><?= Yii::t('app', 'Instagram Accounts') ?></a>
    </h1>

<?= Panel::widget([
    'content' => InstagramTokenGridView::widget([
        'dataProvider' => $provider,
    ]),
]); ?>