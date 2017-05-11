<?php

namespace app\modules\site;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['site']) && !isset($app->i18n->translations['site*'])) {
            $app->i18n->translations['site'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/site/messages',
                'forceTranslation' => true
            ];
        }
    }
}