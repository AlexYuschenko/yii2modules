<?php

namespace app\modules\roles;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['roles']) && !isset($app->i18n->translations['roles*'])) {
            $app->i18n->translations['roles'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/roles/messages',
                'forceTranslation' => true
            ];
        }
    }
}