<?php
return [
    'bootstrap' => ['settings'],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@YiiNodeSocket' => '@vendor/oncesk/yii-node-socket/lib/php',
        '@nodeWeb' => '@vendor/oncesk/yii-node-socket/lib/js'
    ],
    'timeZone' => 'Europe/Moscow',
    'components' => [
        'settings' => [
            'class' => 'common\components\Settings'
        ],
        'phantom' => [
            'class' => 'common\components\Phantom',
            'pathToConfig' => realpath(__DIR__).'/phantom.conf.json'
        ],
        'bookmaker' => [
            'class' => 'common\components\Bookmaker',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Europe/Moscow',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['user']
        ],
        'nodeSocket' => [
            'class' => '\YiiNodeSocket\NodeSocket',
        ],
        'db' => [
            'enableSchemaCache' => true,

            // Duration of schema cache.
            'schemaCacheDuration' => 3600,

            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ]
    ],
];
