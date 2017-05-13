<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Links */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('roles', 'Update role: {role}', ['role' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="auth-item-form">

        <?php $form = ActiveForm::begin();?>

        <?php
            if (in_array($model->name, $this->context->module->defaultRoles) && $model->name == Yii::$app->request->get('name')) {
                $role_options = ['readonly' => 'readonly'];
            } else {
                $role_options = [];
            }
            $role_options['placeholder'] = Yii::t('roles', '* only latin letter, numbers and _ -');
        ?>

        <?= $form->field($model, 'name')->textInput($role_options) ?>

        <?= $form->field($model, 'description')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('roles', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
