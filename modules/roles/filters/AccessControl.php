<?php

namespace app\modules\roles\filters;

use Yii;
use yii\web\User;
use yii\di\Instance;
use yii\base\Module;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

/**
 * Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control. 
 * As its name indicates, ACF is an action filter that can be attached to a controller or a module as a behavior. 
 * ACF will check a set of access rules to make sure the current user can access the requested action.
 *
 * To use AccessControl, declare it in the application config as behavior.
 * For example.
 *
 * ~~~
 * 'as access' => [
 *     'class' => 'app\modules\roles\filters\AccessControl',
 *     'allowActions' => ['site/*', 'user/*']
 * ]
 * ~~~
 *
 * @property User $user
 */
class AccessControl extends ActionFilter
{
    /**
     * @var User User for check access.
     */
    private $_user = 'user';

    /**
     * @var array List of action that not need to check access.
     */
    public $allowActions = [];

    /**
     * Get user
     * @return User
     */
    public function getUser()
    {
        if (!$this->_user instanceof User) {
            $this->_user = Instance::ensure($this->_user, User::className());
        }
        return $this->_user;
    }

    /**
     * Set user
     * @param User|string $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $user = $this->getUser();

        $actionId = $action->getUniqueId();
        if ($user->can($actionId)) {
            return true;
        }

        $controllerId = $action->controller->getUniqueId() . '/*';
        if ($controllerId !== '/*' && $user->can($controllerId)) {
          return true;
        }

        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $moduleId = $action->controller->module->getUniqueId() . '/*';
            if ($moduleId !== '/*' && $user->can($moduleId)) {
              return true;
            }
            if ($action->controller->module->module instanceof Module) {
                $parentModuleId = $action->controller->module->module->getUniqueId() . '/*';
                if ($parentModuleId !== '/*' && $user->can($parentModuleId)) {
                  return true;
                }
            }
        }

        $this->denyAccess($user);
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  yii\web\User $user the current user
     * @throws yii\web\ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if ($user->isGuest) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('roles', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Returns a value indicating whether the filter is active for the given action.
     */
    protected function isActive($action)
    {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        $user = $this->getUser();
        if ($user->getIsGuest() && is_array($user->loginUrl) && isset($user->loginUrl[0]) && $uniqueId === trim($user->loginUrl[0], '/')) {
            return false;
        }

        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $uniqueId;
            if ($mid !== '' && strpos($id, $mid . '/') === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }

        foreach ($this->allowActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, '*');
                if ($route === '' || strpos($id, $route) === 0) {
                    return false;
                }
            } else {
                if ($id === $route) {
                    return false;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return false;
        }

        return true;
    }
}