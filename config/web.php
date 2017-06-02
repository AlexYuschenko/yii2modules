<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'MyApplication',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\modules\admin\Bootstrap',
        'app\modules\site\Bootstrap',
        'app\modules\user\Bootstrap',
        'app\modules\roles\Bootstrap',
        'app\modules\hotel\Bootstrap',
    ],
    'language' => 'uk',
    'timeZone' => 'Europe/Kiev',
    'components' => [
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JLF9aU9Od_Unt-GoTSQv47oLoDuXQbhR',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'loginUrl' => 'login',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/default/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
            ],
            'rules' => [
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'admin',
                    'routePrefix' => 'admin',
                    'rules' => [
                        '' => 'default/index',
                        '<module:[\w\-]+>' => '<module>/default/index',
                        '<module:[\w\-]+>/<id:\d+>' => '<module>/default/view',
                        '<module:[\w\-]+>/<id:\d+>/<action:[\w\-]+>' => '<module>/default/<action>',
                        '<module:[\w\-]+>/<controller:[\w\-]+>' => '<module>/<controller>/index',
                        '<module:[\w\-]+>/<controller:[\w\-]+>/<id:\d+>' => '<module>/<controller>/view',
                        '<module:[\w\-]+>/<controller:[\w\-]+>/<id:\d+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
                    ],
                ],
                '' => 'site/default/index',
                'contact' => 'site/contact/index',
                '<action:error>' => 'site/default/<action>',
                '<action:(login|logout|signup|email-confirm|password-reset-request|password-reset)>' => 'user/default/<action>',
                '<module:[\w\-]+>' => '<module>/default/index',
                '<module:[\w\-]+>/<controller:[\w\-]+>' => '<module>/<controller>/index',
                '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w-]+>' => '<module>/<controller>/<action>',
                '<module:[\w\-]+>/<controller:[\w\-]+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:[\w\-]+>/<controller:[\w\-]+>/<id:\d+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'assetManager' => [
            'forceCopy' => true,
            'bundles' => [
                'app\widgets\gmaplocation\GMapLocationAssets' => [
                    'key' => 'AIzaSyAnBep9owPuKNGZ239F9CbZkMqfR8N7URo',
                    'language' => 'en',
                ]
            ]
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => '@app/views/layouts/admin',
            'modules' => [
                'user' => [
                    'class' => 'app\modules\user\Module',
                    'controllerNamespace' => 'app\modules\user\controllers\backend',
                    'viewPath' => '@app/modules/user/views/backend',
                ],
                'roles' => [
                    'class' => 'app\modules\roles\Module',
                ],
                'hotel' => [
                    'class' => 'app\modules\hotel\Module',
                    'controllerNamespace' => 'app\modules\hotel\controllers\backend',
                    'viewPath' => '@app/modules/hotel/views/backend',
                ],
            ]
        ],
        'site' => [
            'class' => 'app\modules\site\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
            'passwordResetTokenExpire' => 3600,
        ],
        'hotel' => [
            'class' => 'app\modules\hotel\Module',
        ],
    ],
    'as access' => [
        'class' => 'app\modules\roles\filters\AccessControl',
        'allowActions' => [
            'site/*',
            'user/*',
            'gii/*',
            'debug/*',
            'hotel/geo-api/*',
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['127.0.0.1', '192.168.11.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['127.0.0.1', '192.168.11.*'],
    ];
}

return $config;
