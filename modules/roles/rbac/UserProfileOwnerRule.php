<?php

namespace app\modules\roles\rbac;

use Yii;
use yii\rbac\Rule;
use yii\rbac\Item;

class UserProfileOwnerRule extends Rule
{
    public $name = 'profileOwner';

    /**
     * @param string|integer $user   the user ID.
     * @param Item           $item   the role or permission that this rule is associated with
     * @param array          $params parameters passed to ManagerInterface::checkAccess().
     *
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return (isset(Yii::$app->request->queryParams['id']) and Yii::$app->user->id == Yii::$app->request->queryParams['id']) ? true : false;
    }
}
?>