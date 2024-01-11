<?php

namespace app\modules\admin\widgets\panels;

use app\models\InstagramToken;
use app\modules\admin\controllers\InstagramTokenController;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\modules\admin\widgets\panels\HelpPanel;
use Yii;
use yii\helpers\Url;

/**
 *
 */
class InstagramTokenHelpPanel extends HelpPanel
{
    /**
     * @var InstagramToken
     */
    public $model;

    public function init()
    {
        $this->title ??= Yii::t('app', 'Instagram');
        $this->setId($this->getId(false) ?? 'instagram');

        $this->content ??= $this->model->username
            ? $this->renderUpdateInstagramContent()
            : $this->renderCreateInstagramContent();


        parent::init();
    }

    protected function renderCreateInstagramContent(): string
    {
        $text = Yii::t('app', 'Instagram is not linked to this account yet. Connect it by clicking the link below:');
        $content = $this->renderHelpBlock($text);

        $url = $this->model->getLoginUrl();

        $link = Html::tag('div', Html::a($url, $url, ['target' => '_blank']), [
            'id' => 'instagram-link',
            'class' => 'text-break',
        ]);

        $content .= $this->renderHelpBlock($link);

        $button = Html::button(Html::iconText('link', Yii::t('app', 'Copy link')), [
            'class' => 'btn btn-secondary',
            'onclick' => "navigator.clipboard.writeText('$url')",
        ]);

        $content .= $this->renderButtonToolbar($button);

        return $content;
    }

    protected function renderUpdateInstagramContent(): string
    {
        $url = Url::toRoute($this->model->getRoute(), true);

        $text = Yii::t('app', 'Instagram account "{name}" is linked to this account and available via API endpoint {endpoint}.', [
            'name' => $this->model->username,
            'endpoint' => Html::a($url, $url, ['target' => '_blank']),
        ]);

        $buttons = [
            $this->getPreviewButton(),
            $this->getLoginLinkButton(),
            $this->getRefreshButton(),
            $this->getResetButton(),
        ];

        return $this->renderHelpBlock($text) . $this->renderButtonToolbar(array_filter($buttons));
    }

    protected function getLoginLinkButton(): string
    {
        return Html::button(Html::iconText('link', Yii::t('app', 'Show login link')), [
            'class' => 'btn btn-secondary',
            'data-confirm' => Html::tag('div', $this->model->getLoginUrl(), ['class' => 'text-break']),
        ]);
    }

    protected function getPreviewButton(): string
    {
        /** @see InstagramTokenController::actionPreview() */
        return Html::a(Html::iconText('images', Yii::t('app', 'Preview media')), ['preview', 'id' => $this->model->id], [
            'class' => 'btn btn-secondary',
        ]);
    }

    protected function getRefreshButton(): string
    {
        /** @see InstagramTokenController::actionRefresh() */
        return Html::a(Html::iconText('sync', Yii::t('app', 'Refresh token')), ['refresh', 'id' => $this->model->id], [
            'class' => 'btn btn-primary',
            'data-method' => 'post',
        ]);
    }

    protected function getResetButton(): string
    {
        /** @see InstagramTokenController::actionReset() */
        return Html::a(Html::iconText('trash-alt', Yii::t('app', 'Unlink account')), ['reset', 'id' => $this->model->id], [
            'class' => 'btn btn-danger',
            'data-confirm' => Yii::t('app', 'Are you sure you want to reset this Instagram account?'),
            'data-method' => 'post',
        ]);
    }
}