<?php

namespace app\modules\hotel;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['hotel']) && !isset($app->i18n->translations['hotel*'])) {
            $app->i18n->translations['hotel'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/modules/hotel/messages',
                'forceTranslation' => true
            ];
        }
    }
}