<?php

namespace app\modules\admin;

use davidhirtz\yii2\skeleton\models\User;
use Yii;

class Module extends \davidhirtz\yii2\skeleton\modules\admin\Module
{
    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->navbarItems['instagram'] = [
            'label' => Yii::t('app', 'Accounts'),
            'icon' => 'link',
            'url' => ['/admin/instagram-token/index'],
            'active' => ['admin/instagram-token'],
            'roles' => [User::AUTH_ROLE_ADMIN],
        ];
    }
}