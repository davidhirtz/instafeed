<?php

namespace app\modules\admin\widgets\grids;


use app\models\InstagramToken;
use app\modules\admin\controllers\InstagramTokenController;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\skeleton\modules\admin\widgets\grid\GridView;
use davidhirtz\yii2\timeago\TimeagoColumn;
use Yii;

/**
 * The InstagramTokenGridView widget is used to display the {@link InstagramToken} models in a grid.
 */
class InstagramTokenGridView extends GridView
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->columns = [
            $this->nameColumn(),
            $this->usernameColumn(),
            $this->expiresAtColumn(),
            $this->updatedAtColumn(),
            $this->buttonsColumn(),
        ];

        $this->footer = [
            [
                [
                    'content' => Html::buttons([
                        /** @see InstagramTokenController::actionCreate() */
                        Html::a(Html::iconText('plus', Yii::t('app', 'New Account')), ['instagram-token/create'], ['class' => 'btn btn-primary']),
                    ]),
                    'options' => ['class' => 'col'],
                ],
            ],
        ];

        $this->getView()->registerJs('jQuery.timeago.settings.allowFuture = true;');

        parent::init();
    }

    /**
     * @return array
     */
    public function nameColumn(): array
    {
        return [
            'attribute' => 'name',
            'content' => function (InstagramToken $instagram) {
                $name = Html::tag('strong', Html::encode($instagram->name));

                if ($description = $instagram->description) {
                    $name .= Html::tag('div', Html::encode($description), ['class' => 'small']);
                }

                return Html::a($name, $this->getRoute($instagram));
            }
        ];
    }

    /**
     * @return array
     */
    public function usernameColumn(): array
    {
        return [
            'attribute' => 'username',
            'content' => function (InstagramToken $instagram) {
                return ($username = $instagram->username) ? Html::a($username, $this->getRoute($instagram)) : '';
            }
        ];
    }

    /**
     * @return array
     */
    public function updatedAtColumn(): array
    {
        return [
            'class' => TimeagoColumn::class,
            'attribute' => 'updated_at',
            'displayAtBreakpoint' => 'lg',
        ];
    }

    /**
     * @return array
     */
    public function expiresAtColumn(): array
    {
        return [
            'class' => TimeagoColumn::class,
            'attribute' => 'expires_at',
        ];
    }

    /**
     * @return array
     */
    public function buttonsColumn(): array
    {
        return [
            'contentOptions' => ['class' => 'text-right text-nowrap'],
            'content' => function (InstagramToken $group) {
                return Html::buttons([
                    $this->getUpdateButton($group),
                ]);
            }
        ];
    }
}