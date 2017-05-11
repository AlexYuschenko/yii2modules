<?php

namespace app\modules\admin\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package app\modules\admin\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \app\modules\admin\Module|null Module instance
     */
    private $_module;

    /**
     * @return \app\modules\admin\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('users');
        }
        return $this->_module;
    }
}