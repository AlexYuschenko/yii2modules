Yii 2 Modules
============================

Source code of Yii 2 modules.

DIRECTORY STRUCTURE
-------------------

      migrations/         contains modules migrations
      modules/            contains modules
      views/              contains views
      widgets/            contains widget files


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

Execute migrations:

~~~
php yii migrate
~~~

### Modules configuration ###

Configure routes

```php
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
```

Configure `modules` section of your application.

```php
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
```

Configure `bootstrap` section of your application.

```php
    'bootstrap' => [
        'log',
        'app\modules\admin\Bootstrap',
        'app\modules\site\Bootstrap',
        'app\modules\user\Bootstrap',
        'app\modules\roles\Bootstrap',
        'app\modules\hotel\Bootstrap',
        ...
    ],
```

Configure `components` section of your application.

```php
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'loginUrl' => 'login',
            'enableAutoLogin' => true,
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
```

Declare AccessControl in the application config as behavior

```php
    'as access' => [
        'class' => 'app\modules\roles\filters\AccessControl',
        'allowActions' => [
            'site/*',
            'user/*',
            'hotel/geo-api/*',
        ]
    ],
```