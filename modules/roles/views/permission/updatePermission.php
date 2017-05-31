<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Links */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('roles', 'Update permission: {permission}', ['permission' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Permissions'), 'url' => ['permission']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="auth-item-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php
            if (in_array($model->name, $this->context->module->defaultPermissions) && $model->name == Yii::$app->request->get('name')) {
                $permit_options = ['readonly' => 'readonly'];
            } else {
                $permit_options = [];
            }
        ?>

        <?= $form->field($model, 'name')->textInput($permit_options) ?>

        <?= $form->field($model, 'description')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('roles', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>