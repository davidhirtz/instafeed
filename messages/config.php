<?php
/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yii message' command.
 */
return [
    'sourcePath' => dirname(__DIR__),
    'messagePath' => __DIR__,
    'languages' => ['de', 'en-US'],
    'ignoreCategories' => ['yii', 'skeleton', 'cms', 'media'],
    'overwrite' => true,
    'only' => ['*.php'],
    'format' => 'php',
    'sort' => true,
    'except' => [
        '/assets',
        '/config',
        '/messages',
        '/runtime',
        '/tests',
        '/vendor',
        '/web',
    ],
];
