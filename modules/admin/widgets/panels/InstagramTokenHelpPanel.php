<?php

namespace app\modules\admin\widgets\panels;

use app\models\InstagramToken;
use app\modules\admin\controllers\InstagramTokenController;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\modules\admin\widgets\panels\HelpPanel;
use Yii;

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
        if ($this->title === null) {
            $this->title = Yii::t('app', 'Setup');
        }

        if ($this->content === null) {
            $this->content = $this->renderButtonToolbar(array_filter($this->getButtons()));
        }

        parent::init();
    }

    /**
     * @return array
     */
    protected function getButtons(): array
    {
        if ($this->model->username) {
            return [
                $this->getPreviewButton(),
                $this->getRefreshButton(),
                $this->getResetButton(),
            ];
        }

        return [$this->getLoginLinkButton()];
    }


    /**
     * @return string
     */
    protected function getLoginLinkButton()
    {
        return Html::button(Html::iconText('clipboard', Yii::t('app', 'Show login link')), [
            'class' => 'btn btn-secondary',
            'data-confirm' => Html::tag('div', $this->model->getLoginUrl(), ['class' => 'text-break']),
        ]);
    }

    /**
     * @return string
     */
    protected function getPreviewButton()
    {
        /** @see InstagramTokenController::actionPreview() */
        return Html::a(Html::iconText('images', Yii::t('app', 'Preview media')), ['preview', 'id' => $this->model->id], [
            'class' => 'btn btn-primary',
        ]);
    }

    /**
     * @return string
     */
    protected function getRefreshButton()
    {
        /** @see InstagramTokenController::actionRefresh() */
        return Html::a(Html::iconText('sync', Yii::t('app', 'Refresh token')), ['refresh', 'id' => $this->model->id], [
            'class' => 'btn btn-primary',
            'data-method' => 'post',
        ]);
    }

    /**
     * @return string
     */
    protected function getResetButton()
    {
        /** @see InstagramTokenController::actionReset() */
        return Html::a(Html::iconText('trash-alt', Yii::t('app', 'Reset account')), ['reset', 'id' => $this->model->id], [
            'class' => 'btn btn-danger',
            'data-confirm' => Yii::t('app', 'Are you sure you want to reset this Instagram account?'),
            'data-method' => 'post',
        ]);
    }
}