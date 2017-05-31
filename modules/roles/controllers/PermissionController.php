<?php

namespace app\modules\roles\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\rbac\Role;
use yii\rbac\Permission;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\RegularExpressionValidator;
use app\modules\roles\models\AuthRole;
use app\modules\roles\models\AuthPermission;
use app\modules\roles\models\Rule;
use app\modules\roles\models\RuleSearch;
use yii\filters\VerbFilter;

class PermissionController extends Controller
{
    protected $pattern4Permission = '/^[a-zA-Z0-9_\*\/-]+$/';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        return $this->render('permission');
    }

    /**
     * @inheritdoc
     */
    public function actionAddPermission()
    {
        $model = new AuthPermission();
        if ($model->load(Yii::$app->request->post())
            && $this->validate($this->clear($model->name), $this->pattern4Permission)
            && $this->isUnique($this->clear($model->name))
        ) {
            $permit = Yii::$app->authManager->createPermission($this->clear($model->name));
            $permit->description = $model->description;
            Yii::$app->authManager->add($permit);
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Permission has been created'));
            return $this->redirect(Url::toRoute(['permission/index']));
        }

        return $this->render('addPermission', [
            'model' => $model
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actionUpdatePermission($name)
    {
        $model = new AuthPermission();
        $permit = Yii::$app->authManager->getPermission($name);

        if ($permit instanceof Permission) {
            if ($model->load(Yii::$app->request->post())
                && $this->validate($model->name, $this->pattern4Permission)
            ) {
                if ($model->name != $name && !$this->isUnique($model->name)) {
                    return $this->render('updatePermission', [
                        'model' => $model
                    ]);
                }
                $permit->name = $model->name;
                $permit->description = $model->description;
                Yii::$app->authManager->update($name, $permit);
                Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Permission have been updated'));
                return $this->redirect(Url::toRoute(['permission/index']));
            }

            $model->name = $permit->name;
            $model->description = $permit->description;

            return $this->render('updatePermission', [
                'model' => $model
            ]);
        } else {
            throw new BadRequestHttpException(Yii::t('roles', 'Page not found'));
        }
    }

    /**
     * @inheritdoc
     */
    public function actionDeletePermission($name)
    {
        $permit = Yii::$app->authManager->getPermission($name);
        if ($permit) {
            Yii::$app->authManager->remove($permit);
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Permission have been deleted'));
        }
        return $this->redirect(Url::toRoute(['permission/index']));
    }

    /**
     * @inheritdoc
     */
    protected function validate($field, $regex)
    {
        $validator = new RegularExpressionValidator(['pattern' => $regex]);
        if ($validator->validate($field, $error)) {
            return true;
        } else {
            Yii::$app->getSession()->setFlash('error', Yii::t('roles', 'Value "{field}" contains not allowed symbols', ['field' => $field]));
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    protected function isUnique($name)
    {
        $permission = Yii::$app->authManager->getPermission($name);
        if ($permission instanceof Permission) {
            Yii::$app->getSession()->setFlash('error', Yii::t('roles', 'Permission already exists: {permission}', [
                'permission' => $name
            ]));
            return false;
        } else {
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    protected function clear($value)
    {
        if (!empty($value)) {
            $value = trim($value, "/ \t\n\r\0\x0B");
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function actionAddRolePermission()
    {
        $role = Yii::$app->request->get('role');
        $permission = Yii::$app->request->get('permission');

        $role = Yii::$app->authManager->getRole($role);
        $permission = Yii::$app->authManager->getPermission($permission);

        Yii::$app->authManager->addChild($role, $permission);

        return $this->redirect(Url::toRoute(['permission/index']));
    }

    /**
     * @inheritdoc
     */
    public function actionRemoveRolePermission()
    {
        $role = Yii::$app->request->get('role');
        $permission = Yii::$app->request->get('permission');

        $role = Yii::$app->authManager->getRole($role);
        $permission = Yii::$app->authManager->getPermission($permission);

        Yii::$app->authManager->removeChild($role, $permission);

        return $this->redirect(Url::toRoute(['permission/index']));
    }
}
