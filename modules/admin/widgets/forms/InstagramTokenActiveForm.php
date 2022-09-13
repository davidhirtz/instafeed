<?php

namespace app\modules\admin\widgets\forms;

use app\controllers\ApiController;
use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\widgets\bootstrap\ActiveForm;
use Yii;

/**
 * InstagramTokenActiveForm is a widget that builds an interactive HTML form for the {@link InstagramToken} model.
 * @property InstagramToken $model
 */
class InstagramTokenActiveForm extends ActiveForm
{
    public $hasStickyButtons = true;

    /**
     * @return void
     */
    public function renderFields()
    {
        echo $this->field($this->model, 'name');
        echo $this->field($this->model, 'description')->textarea(['rows' => 2]);

        $items = [
            0 => Yii::t('app', 'Disabled'),
            60 * 5 => Yii::t('app', '5 minutes'),
            60 * 15 => Yii::t('app', '15 minutes'),
            60 * 30 => Yii::t('app', '30 minutes'),
            60 * 60 => Yii::t('app', '1 hour'),
        ];

        echo $this->field($this->model, 'cache_duration')->dropdownList($items);

        /** @see ApiController::actionIndex() */
        $hostInfo = rtrim(Yii::$app->getRequest()->getHostInfo(), '/') . '/';
        echo $this->field($this->model, 'slug', ['enableClientValidation' => false])
            ->prependInput($hostInfo);

        if ($token = $this->model->access_token) {
            echo $this->horizontalLine();
            echo $this->plainTextRow($this->model->getAttributeLabel('username'), "{$this->model->username} ({$this->model->user_id})");

            $url = "https://developers.facebook.com/tools/debug/accesstoken/?access_token={$token}";
            echo $this->plainTextRow($this->model->getAttributeLabel('access_token'), Html::a($token, $url, ['target' => '_blank']));

            echo $this->plainTextRow($this->model->getAttributeLabel('refreshed_at'), $this->model->refreshed_at ? Yii::$app->getFormatter()->asDatetime($this->model->refreshed_at) : '–');
            echo $this->plainTextRow($this->model->getAttributeLabel('expires_at'), $this->model->expires_at ? Yii::$app->getFormatter()->asDatetime($this->model->expires_at) : '–');
        }
    }
}