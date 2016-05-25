<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'nodeSocket', 'session'],
    'controllerNamespace' => 'frontend\controllers',
    'layout' => 'main',
    'components' => [
        'session' => [
            'class' => 'common\components\Session'
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
                    ]
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            //'suffix' => '.html',
            'rules' => [
                [
                    'pattern'   => '/login',
                    'route'     => '/site/login',
                ],
                [
                    'pattern'   => '/logout',
                    'route'     => '/site/logout',
                ],
                [
                    'pattern'   => '/',
                    'route'     => '/site/index',
                ],
                [
                    'pattern'   => '/<controller>/<action>',
                    'route'     => '/<controller>/<action>',
                ],
                [
                    'pattern'   => '/<module>/<controller>/<action>',
                    'route'     => '/<module>/<controller>/<action>',
                ],
            ],
        ],
        'view' => [
            'class' => 'common\components\View',
            'theme' => [
                'class'     => 'common\components\Theme',
                'name'      => 'new',
                'basePath'  => '@frontend/themes/new',
                'baseUrl'   => '@web/themes/new',
                'pathMap'   => [
                    '@frontend/views'           => '@frontend/themes/new/www',
                    '@frontend/views/layouts'   => '@frontend/themes/new/layouts',
                    '@frontend/modules'         => '@frontend/themes/new/modules',
                ],
            ],
        ],
    ],
    'params' => $params,
];
