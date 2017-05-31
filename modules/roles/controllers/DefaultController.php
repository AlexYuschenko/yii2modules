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

class DefaultController extends Controller
{
    protected $pattern4Role = '/^[a-zA-Z0-9_-]+$/';

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
        return $this->render('role');
    }

    /**
     * @inheritdoc
     */
    public function actionAddRole()
    {
        $model = new AuthRole();

        if ($model->load(Yii::$app->request->post())
            && $this->validate($model->name, $this->pattern4Role)
            && $this->isUnique($model->name)
        ) {
            $role = Yii::$app->authManager->createRole($model->name);
            $role->description = $model->description;
            Yii::$app->authManager->add($role);
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Role has been created'));
            return $this->redirect(Url::toRoute(['index']));
        }

        return $this->render('addRole', [
            'model' => $model
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actionUpdateRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        $model = new AuthRole();

        if ($role instanceof Role) {
            if ($model->load(Yii::$app->request->post())
                && $this->validate($model->name, $this->pattern4Role)
            ) {
                if ($model->name != $name && !$this->isUnique($model->name)) {
                    return $this->render('updateRole', [
                        'model' => $model
                    ]);
                }
                $role->name = $model->name;
                $role->description = $model->description;
                Yii::$app->authManager->update($name, $role);
                Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Role have been updated'));
                return $this->redirect(Url::toRoute(['index']));
            }

            $model->name = $role->name;
            $model->description = $role->description;

            return $this->render('updateRole', [
                'model' => $model,
            ]);
        } else {
            throw new BadRequestHttpException(Yii::t('roles', 'Page not found'));
        }
    }

    /**
     * @inheritdoc
     */
    public function actionDeleteRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        if ($role) {
            Yii::$app->authManager->removeChildren($role);
            Yii::$app->authManager->remove($role);
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Role have been deleted'));
        }
        return $this->redirect(Url::toRoute(['index']));
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
        $role = Yii::$app->authManager->getRole($name);
        if ($role instanceof Role) {
            Yii::$app->getSession()->setFlash('error', Yii::t('roles', 'Role already exists: {role}', ['role' => $name]));
            return false;
        } else {
            return true;
        }
    }
}
