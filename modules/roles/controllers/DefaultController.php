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
use app\modules\roles\models\Rule;
use app\modules\roles\models\RuleSearch;
use yii\filters\VerbFilter;

class DefaultController extends Controller
{
    protected $error;
    protected $pattern4Role = '/^[a-zA-Z0-9_-]+$/';
    protected $pattern4Permission = '/^[a-zA-Z0-9_\/-]+$/';

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
    public function behaviors() {
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
    public function actionUpdateRule($name) {
        $request = Yii::$app->request;
        $model = $this->findModel($name);

        if ($model->load($request->post()) && $model->save()) {
            //return $this->redirect(['view', 'name' => $model->name]);
            return $this->refresh();
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
    public function actionDeleteRule($name) {
        $request = Yii::$app->request;
        $this->findModel($name)->delete();

        return $this->redirect(Url::toRoute(['rule']));
    }

    /**
     * Finds the Rule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name
     * @return Rule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name) {
        if (($model = Rule::find($name)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('roles', 'The requested page does not exist.'));
        }
    }

    public function actionIndex()
    {
        return $this->render('role');
    }

    public function actionAddRole()
    {
        if (Yii::$app->request->post('name')
            && $this->validate(Yii::$app->request->post('name'), $this->pattern4Role)
            && $this->isUnique(Yii::$app->request->post('name'), 'role')
        ) {
            $role = Yii::$app->authManager->createRole(Yii::$app->request->post('name'));
            $role->description = Yii::$app->request->post('description');
            Yii::$app->authManager->add($role);
            $this->setPermissions(Yii::$app->request->post('permissions', []), $role);
            return $this->redirect(Url::toRoute([
                'update-role',
                'name' => $role->name
            ]));
        }

        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');
        return $this->render(
            'addRole',
            [
                'permissions' => $permissions,
                'error' => $this->error
            ]
        );
    }

    public function actionUpdateRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);

        $permissions = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');
        $role_permit = array_keys(Yii::$app->authManager->getPermissionsByRole($name));

        if ($role instanceof Role) {
            if (Yii::$app->request->post('name')
                && $this->validate(Yii::$app->request->post('name'), $this->pattern4Role)
            ) {
                if (Yii::$app->request->post('name') != $name && !$this->isUnique(Yii::$app->request->post('name'), 'role')) {
                    return $this->render(
                        'updateRole',
                        [
                            'role' => $role,
                            'permissions' => $permissions,
                            'role_permit' => $role_permit,
                            'error' => $this->error
                        ]
                    );
                }
                $role = $this->setAttribute($role, Yii::$app->request->post());
                Yii::$app->authManager->update($name, $role);
                Yii::$app->authManager->removeChildren($role);
                $this->setPermissions(Yii::$app->request->post('permissions', []), $role);
                return $this->redirect(Url::toRoute([
                    'update-role',
                    'name' => $role->name
                ]));
            }

            return $this->render(
                'updateRole',
                [
                    'role' => $role,
                    'permissions' => $permissions,
                    'role_permit' => $role_permit,
                    'error' => $this->error
                ]
            );
        } else {
            throw new BadRequestHttpException(Yii::t('roles', 'Page not found'));
        }
    }

    public function actionDeleteRole($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        if ($role) {
            Yii::$app->authManager->removeChildren($role);
            Yii::$app->authManager->remove($role);
        }
        return $this->redirect(Url::toRoute(['role']));
    }


    public function actionPermission()
    {
        return $this->render('permission');
    }

    public function actionAddPermission()
    {
        $permission = $this->clear(Yii::$app->request->post('name'));
        if ($permission
            && $this->validate($permission, $this->pattern4Permission)
            && $this->isUnique($permission, 'permission')
        ) {
            $permit = Yii::$app->authManager->createPermission($permission);
            $permit->description = Yii::$app->request->post('description', '');
            Yii::$app->authManager->add($permit);
            return $this->redirect(Url::toRoute([
                'update-permission',
                'name' => $permit->name
            ]));
        }

        return $this->render('addPermission', ['error' => $this->error]);
    }

    public function actionUpdatePermission($name)
    {
        $permit = Yii::$app->authManager->getPermission($name);
        if ($permit instanceof Permission) {
            $permission = $this->clear(Yii::$app->request->post('name'));
            if ($permission && $this->validate($permission, $this->pattern4Permission)
            ) {
                if($permission!= $name && !$this->isUnique($permission, 'permission'))
                {
                    return $this->render('updatePermission', [
                        'permit' => $permit,
                        'error' => $this->error
                    ]);
                }

                $permit->name = $permission;
                $permit->description = Yii::$app->request->post('description', '');
                Yii::$app->authManager->update($name, $permit);
                return $this->redirect(Url::toRoute([
                    'update-permission',
                    'name' => $permit->name
                ]));
            }

            return $this->render('updatePermission', [
                'permit' => $permit,
                'error' => $this->error
            ]);
        } else throw new BadRequestHttpException(Yii::t('roles', 'Page not found'));
    }

    public function actionDeletePermission($name)
    {
        $permit = Yii::$app->authManager->getPermission($name);
        if ($permit)
            Yii::$app->authManager->remove($permit);
        return $this->redirect(Url::toRoute(['permission']));
    }

    protected function setAttribute($object, $data)
    {
        $object->name = $data['name'];
        $object->description = $data['description'];
        return $object;
    }

    protected function setPermissions($permissions, $role)
    {
        foreach ($permissions as $permit) {
            $new_permit = Yii::$app->authManager->getPermission($permit);
            Yii::$app->authManager->addChild($role, $new_permit);
        }
    }

    protected function validate($field, $regex)
    {
        $validator = new RegularExpressionValidator(['pattern' => $regex]);
        if ($validator->validate($field, $error))
            return true;
        else {
            $this->error[] = Yii::t('roles', 'Field "{field}" contains not allowed symbols', ['field' => $field]);
            return false;
        }
    }

    protected function isUnique($name, $type)
    {
        if ($type == 'role') {
            $role = Yii::$app->authManager->getRole($name);
            if ($role instanceof Role) {
                $this->error[] = Yii::t('roles', 'Role already exists: ') . $name;
                return false;
            } else return true;
        } elseif ($type == 'permission') {
            $permission = Yii::$app->authManager->getPermission($name);
            if ($permission instanceof Permission) {
                $this->error[] = Yii::t('roles', 'Rule already exists: ') . $name;
                return false;
            } else return true;
        }
    }

    protected function clear($value)
    {
        if (!empty($value)) {
            $value = trim($value, "/ \t\n\r\0\x0B");
        }

        return $value;
    }
}