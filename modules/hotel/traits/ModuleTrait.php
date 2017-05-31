<?php

namespace app\modules\hotel\traits;

use Yii;

/**
 * Class ModuleTrait
 * @package app\modules\hotel\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \app\modules\hotel\Module|null Module instance
     */
    private $_module;

    /**
     * @return \app\modules\hotel\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Yii::$app->getModule('hotel');
        }
        return $this->_module;
    }
}