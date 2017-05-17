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
            && $this->isUnique($model->name, 'role')
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
                if ($model->name != $name && !$this->isUnique($model->name, 'role')) {
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
    public function actionPermission()
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
            && $this->isUnique($this->clear($model->name), 'permission')
        ) {
            $permit = Yii::$app->authManager->createPermission($this->clear($model->name));
            $permit->description = $model->description;
            Yii::$app->authManager->add($permit);
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Permission has been created'));
            return $this->redirect(Url::toRoute(['permission']));
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
                if ($model->name != $name && !$this->isUnique($model->name, 'permission')) {
                    return $this->render('updatePermission', [
                        'model' => $model
                    ]);
                }
                $permit->name = $model->name;
                $permit->description = $model->description;
                Yii::$app->authManager->update($name, $permit);
                Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Permission have been updated'));
                return $this->redirect(Url::toRoute(['permission']));
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
        return $this->redirect(Url::toRoute(['permission']));
    }

    /**
     * @inheritdoc
     */
    protected function setPermissions($permissions, $role)
    {
        foreach ($permissions as $permit) {
            $new_permit = Yii::$app->authManager->getPermission($permit);
            Yii::$app->authManager->addChild($role, $new_permit);
        }
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
    protected function isUnique($name, $type)
    {
        if ($type == 'role') {
            $role = Yii::$app->authManager->getRole($name);
            if ($role instanceof Role) {
                Yii::$app->getSession()->setFlash('error', Yii::t('roles', 'Role already exists: {role}', ['role' => $name]));
                return false;
            } else return true;
        } elseif ($type == 'permission') {
            $permission = Yii::$app->authManager->getPermission($name);
            if ($permission instanceof Permission) {
                Yii::$app->getSession()->setFlash('error', Yii::t('roles', 'Permission already exists: {permission}', ['permission' => $name]));
                return false;
            } else return true;
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
     * Lists all Rules.
     * @return mixed
     */
    public function actionRule()
    {
        $searchModel = new RuleSearch(null);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('rule', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Rule model.
     * @return mixed
     */
    public function actionAddRule()
    {
        $request = Yii::$app->request;
        $model = new Rule(null);

        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Rule has been created'));
            return $this->redirect(Url::toRoute(['rule']));
        } else {
            return $this->render('addRule', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Rule model.
     * @param string $name
     * @return mixed
     */
    public function actionUpdateRule($name)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($name);

        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Rule have been updated'));
            return $this->redirect(Url::toRoute(['rule']));
        } else {
            return $this->render('updateRule', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Delete an existing Rule model.
     * @param string $name
     * @return mixed
     */
    public function actionDeleteRule($name)
    {
        $request = Yii::$app->request;
        $this->findModel($name)->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Rule have been deleted'));
        return $this->redirect(Url::toRoute(['rule']));
    }

    /**
     * Finds the Rule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name
     * @return Rule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = Rule::find($name)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('roles', 'The requested page does not exist.'));
        }
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

        return $this->redirect(Url::toRoute(['permission']));
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

        return $this->redirect(Url::toRoute(['permission']));
    }
}