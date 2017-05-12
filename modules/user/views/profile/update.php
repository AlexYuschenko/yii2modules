<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\user\forms\ProfileUpdateForm */

$this->title = Yii::t('user', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Profile'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin(['id' => 'profile-update-form']); ?>
        <div class="row">
            <div class="col-md-7">
              <?= $form->field($model, 'first_name') ?>

              <?= $form->field($model, 'last_name') ?>

              <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
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
                            'class' => 'file-preview-image img-thumbnail',
                            'width' => '150'
                            
                        ]);
                    }
                    echo Html::tag('div', $avatar, ['class' => 'file-preview-frame']);

                    echo $form->field($model, 'avatarImage')->label('')->fileInput();
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>