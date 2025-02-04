<?php

use yii\web\View;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'class' => 'yii\web\Request',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'authenticator' => [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'except' => ['auth/login', 'auth/signup'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
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
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/card',
                    'extraPatterns' => [
                        'GET {id}/countlistings' => 'countlistings',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/favorite',
                    'extraPatterns' => [
                        'DELETE <cardId:\d+>' => 'delete',
                    ],
                ],
                ['class'=>'yii\rest\UrlRule', 'controller'=>'api/game'],
                ['class'=>'yii\rest\UrlRule', 'controller'=>'api/listing'],
                ['class'=>'yii\rest\UrlRule', 'controller'=>'api/product'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/auth'],

                'GET,HEAD api/invoices' => 'api/invoice/index',
                'GET,HEAD api/invoices/<id:\d+>' => 'api/invoice/view',
                'POST api/invoices/<id:\d+>/status' => 'api/invoice/update-status',
                'POST api/invoices' => 'api/invoice/create',

                'GET api/user/email' => 'api/user/get-email',
                'POST api/user/username' => 'api/user/change-username',
                'POST api/user/email' => 'api/user/change-email',
            ],
        ],
        'view' => [
            'class' => View::class,
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@backend/views'
                ],
            ],
        ],
    ],
    'params' => $params,
];
