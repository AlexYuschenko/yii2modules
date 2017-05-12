<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('roles', 'Update Rule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Rule manager'), 'url' => ['rule']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-update">

  <h2><?= Html::encode($this->title) ?></h2>

  <div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'className')->textInput(['maxlength' => true, 'placeholder' => 'app\\rbac\\ClassName']) ?>

    <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
            <?= Html::submitButton(Yii::t('roles', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
    
  </div>

</div>
