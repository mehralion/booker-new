<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
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
    ],
];
