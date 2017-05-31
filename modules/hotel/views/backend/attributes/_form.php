<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\Attributes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="attributes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownlist($model->attributeTypeArray) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('hotel', 'Create') : Yii::t('hotel', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
