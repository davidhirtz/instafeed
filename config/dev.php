<?php

/**
 * Development config.
 */
return \yii\helpers\ArrayHelper::merge(require(__DIR__ . '/prod.php'), [
    'components' => [
        'mailer' => [
            'useFileTransport' => true,
        ],
    ],
//    'container' => [
//        'singletons' => [
//            'davidhirtz\yii2\cms\modules\admin\data\EntryActiveDataProvider' => [
//                'pagination' => [
//                    'defaultPageSize' => 2,
//                ],
//            ],
//        ],
//    ],
]);