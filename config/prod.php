<?php

/**
 * @author David Hirtz <hello@davidhirtz.com>
 */
return [
    'name' => 'Instagram Token Management',
    'components' => [
        'urlManager' => [
            'rules' => [
                '<action:authorize|login>' => 'site/<action>',
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
];