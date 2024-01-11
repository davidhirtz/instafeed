<?php

/**
 * @author David Hirtz <hello@davidhirtz.com>
 */
return [
    'name' => 'Instagram Token Management',
    'components' => [
        'user' => [

        ],
        'urlManager' => [
            'rules' => [
                '<action:authorize|deleted|login>' => 'auth/<action>',
                '<slug>' => 'api/index',
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
];