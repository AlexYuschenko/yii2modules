<?php

namespace app\modules\user\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package app\modules\user\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \app\modules\user\Module|null Module instance
     */
    private $_module;

    /**
     * @return \app\modules\user\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('user');
        }
        return $this->_module;
    }
}