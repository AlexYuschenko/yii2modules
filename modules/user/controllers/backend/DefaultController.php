<?php

namespace app\modules\user\controllers\backend;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\modules\user\models\backend\User;
use app\modules\user\models\UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class DefaultController extends Controller
{
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Url::remember('', 'actions-redirect');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
        ]);
        if ($model->load(Yii::$app->request->post())) {
            $model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');
            if ($model->avatarImage && $model->avatarImage->tempName) {
                if ($model->validate('avatarImage')) {
                    $dir = 'uploads/avatars/';
                    $fileName = time() . '.' . $model->avatarImage->extension;
                    $model->avatarImage->saveAs($dir . $fileName);
                    $model->avatar = $fileName;
                    $model->avatarImage = null;
                }
            }
            if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been created'));
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
            $model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');
            if ($model->avatarImage && $model->avatarImage->tempName) {
                if ($model->validate('avatarImage')) {
                    $dir = 'uploads/avatars/';
                    $fileName = time() . '.' . $model->avatarImage->extension;
                    $model->avatarImage->saveAs($dir . $fileName);
                    @unlink($dir . $model->avatar);
                    $model->avatar = $fileName;
                    $model->avatarImage = null;
                }
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));
                return $this->refresh();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteAvatar($id)
    {
        $model = $this->findModel($id);
        if (@unlink('uploads/avatars/' . $model->avatar)) {
            $model->avatar = null;
            $model->save();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Avatar deleted'));
        }

        return $this->redirect(['update', 'id' => $model->id]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        @unlink('uploads/avatars/' . $model->avatar);
        $model->delete();
        Yii::$app->authManager->revokeAll($id);

        return $this->redirect(['index']);
    }

    /**
     * This page displays form where user can assign role.
     * @param  integer $id
     * @return string
     */
    public function actionAssignment($id)
    {
        Url::remember('', 'actions-redirect');
        $model = $this->findModel($id);
        $model->scenario = 'assignment';

        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'You can not change your own role'));
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->updateUserRole()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User role have been updated'));
                return $this->refresh();
            }
        }

        return $this->render('assignment', [
            'model' => $model,
        ]);
    }

    /**
     * Blocks the user.
     * @param  integer $id
     * @return Response
     */
    public function actionBlock($id)
    {
        if ($id == Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'You can not block your own account'));
        } else {
            $user = $this->findModel($id);
            if ($user->getIsBlocked()) {
                $user->unblock();
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been unblocked'));
            } else {
                $user->block();
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been blocked'));
            }
        }

        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
