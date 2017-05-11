<?php

namespace app\modules\user;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['user']) && !isset($app->i18n->translations['user*'])) {
            $app->i18n->translations['user'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/user/messages',
                'forceTranslation' => true
            ];
        }
    }
}