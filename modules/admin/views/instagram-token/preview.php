<?php
/**
 * @see \app\modules\admin\controllers\InstagramTokenController::actionPreview()
 *
 * @var View $this
 * @var InstagramToken $instagram
 */

use app\assets\PreviewAssetBundle;
use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\web\View;
use yii\helpers\Url;

$this->setTitle(Yii::t('app', 'Instagram Account Preview'));

$this->setBreadcrumbs([
    Yii::t('app', 'Accounts') => ['index'],
    Html::encode($instagram->name) => ['update', 'id' => $instagram->id],
]);

$bundle = PreviewAssetBundle::register($this);
$url = Yii::$app->getUrlManager()->createAbsoluteUrl($instagram->getRoute())
?>
<style>
    .instagram-item {
        padding: 15px;
        width: 50%;
    }

    @media (min-width: 768px) {
        .instagram-item {
            width: 25%;
        }
    }
</style>
<h1 class="page-header">
    <a href="<?= Url::toRoute(['index']) ?>"><?= Yii::t('app', 'Instagram Accounts') ?></a>
</h1>
<div class="card card-default">
    <div class="card-header"><h2 class="card-title">Preview Instagram Media</h2></div>
    <div class="card-body">
        <div class="alert alert-info">
            <?= Yii::t('app', 'This is a preview of the results returned by API endpoint {url}.', [
                'url' => Html::a($url, $url, [
                    'target' => '_blank',
                ]),
            ]); ?>
        </div>
        <div id="instagram" class="row"></div>
    </div>
</div>
<script type="module">
    import Instagram from "<?= $bundle->baseUrl; ?>/instagram.js";

    new Instagram({
        url: '<?= $url; ?>',
        $container: document.getElementById('instagram'),
        lazyload: false,
        maxItems: 8,
    });
</script>
