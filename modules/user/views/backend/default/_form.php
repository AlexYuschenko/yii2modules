<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\backend\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['enctype'=>'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-7">

            <?= $form->field($model, 'username')->textInput(['readonly' => !$model->isNewRecord]); ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'newPassword')->passwordInput(); ?>

            <?= $form->field($model, 'newPasswordRepeat')->passwordInput(); ?>

            <?php $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'); unset($roles['guest']); ?>
            <?= $form->field($model, 'role')->checkboxList($roles) ?>

            <?= $form->field($model, 'first_name') ?>

            <?= $form->field($model, 'last_name') ?>

            <?= $form->field($model, 'status')->dropDownList($model->statusesArray, ['prompt' => Yii::t('user', 'Select status')]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        </div>
        <div class="col-md-5">
            <?php
                $avatar_path = Yii::$app->urlManager->baseUrl . '/uploads/avatars/';
                if (empty($model->avatar)) {
                    $avatar = Html::img($avatar_path . 'default_avatar.png', [
                        'alt' => Yii::t('user', 'No avatar yet'),
                        'title' => Yii::t('user', 'Upload your avatar by selecting browse below'),
                        'class' => 'file-preview-image',
                    ]);
                }
                else {
                    $avatar = Html::img($avatar_path . $model->avatar, [
                        'alt' => Yii::t('user', 'Avatar for ') . $model->username,
                        'title' => Yii::t('user', 'Click remove button below to remove this image'),
                        'class' => 'file-preview-image img-thumbnail',
                        'width' => '150'
                        
                    ]);
                }
                echo Html::tag('div', $avatar, ['class' => 'file-preview-frame']);

                if (!empty($model->avatar)) {
                    echo Html::a(
                        Yii::t('user', 'Remove avatar'), 
                        ['delete-avatar', 'id' => $model->id],
                        ['class' => 'btn btn-danger']
                    );
                }
                echo $form->field($model, 'avatarImage')->label('')->fileInput();
            ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
