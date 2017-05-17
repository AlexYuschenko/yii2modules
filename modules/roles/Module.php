<?php

namespace app\modules\roles;

use Yii;

/**
 * roles module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $defaultRoles = ['guest', 'user', 'manager', 'admin'];
    public $defaultPermissions = ['admin/*'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
