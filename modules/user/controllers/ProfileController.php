<?php

namespace app\modules\user\controllers;

use app\modules\user\forms\PasswordChangeForm;
use app\modules\user\forms\ProfileUpdateForm;
use app\modules\user\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    /** 
    * @inheritdoc 
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /** 
    * @inheritdoc 
    */
    public function actionIndex()
    {
        return $this->render('index', [
            'model' => $this->findModel(),
        ]);
    }

    /** 
    * @inheritdoc 
    */
    public function actionUpdate()
    {
        $user = $this->findModel();
        $model = new ProfileUpdateForm($user);

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
            if ($model->update()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /** 
    * @inheritdoc 
    */
    public function actionPasswordChange()
    {
        $user = $this->findModel();
        $model = new PasswordChangeForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your passwords is changed.'));
            return $this->redirect(['index']);
        }

        return $this->render('passwordChange', [
            'model' => $model,
        ]);
    }

    /**
     * @return User the loaded model
     */
    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }
}