<?php

/**
 * @author David Hirtz <hello@davidhirtz.com>
 */
return [
    'name' => 'Instagram Token Management',
    'components' => [
        'urlManager' => [
            'rules' => [
                '<action:authorize|login>' => 'auth/<action>',
                '<slug>' => 'api/',
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
];