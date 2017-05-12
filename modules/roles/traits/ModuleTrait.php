<?php

namespace app\modules\roles\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package app\modules\roles\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \app\modules\roles\Module|null Module instance
     */
    private $_module;

    /**
     * @return \app\modules\roles\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('roles');
        }
        return $this->_module;
    }
}