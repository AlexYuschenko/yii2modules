<?php

namespace app\modules\admin;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['admin']) && !isset($app->i18n->translations['admin*'])) {
            $app->i18n->translations['admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/admin/messages',
                'forceTranslation' => true
            ];
        }
    }
}