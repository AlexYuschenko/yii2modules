Yii 2 Modules
============================

Source code of Yii 2 modules.

DIRECTORY STRUCTURE
-------------------

      migrations/         contains modules migrations
      modules/            contains modules
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
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        [
            'class' => 'yii\web\GroupUrlRule',
            'prefix' => 'admin',
            'routePrefix' => 'admin',
            'rules' => [
                '' => 'default/index',
                '<_m:[\w\-]+>' => '<_m>/default/index',
                '<_m:[\w\-]+>/<id:\d+>' => '<_m>/default/view',
                '<_m:[\w\-]+>/<id:\d+>/<_a:[\w-]+>' => '<_m>/default/<_a>',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
                '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
            ],
        ],
 
        '' => 'main/default/index',
        'contact' => 'main/contact/index',
        '<_a:error>' => 'main/default/<_a>',
 
        '<_a:(login|logout|signup|email-confirm|password-reset-request|password-reset)>' => 'user/default/<_a>',
 
        '<_m:[\w\-]+>' => '<_m>/default/index',
        '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
        '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w-]+>' => '<_m>/<_c>/<_a>',
        '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
        '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
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
        ]
    ],
    'site' => [
        'class' => 'app\modules\site\Module',
    ],
    'user' => [
        'class' => 'app\modules\user\Module',
        'passwordResetTokenExpire' => 3600,
    ],
    ...
],
```

Configure `bootstrap` section of your application.

```php
'bootstrap' => [
    'app\modules\admin\Bootstrap',
    'app\modules\site\Bootstrap',
    'app\modules\user\Bootstrap',
    ...
],
```