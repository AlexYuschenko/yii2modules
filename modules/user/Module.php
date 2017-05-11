<?php

namespace app\modules\user;

use Yii;

/**
 * user module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\user\controllers';

    public $passwordResetTokenExpire = 3600;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
