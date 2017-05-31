<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Links */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('roles', 'New Permission');
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Permissions'), 'url' => ['permission']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="auth-item-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput()->hint(Yii::t('roles', '* Format module/controller/action<br> site/article - access to page site/article<br> site/* - access to all actions at site controller')) ?>

        <?= $form->field($model, 'description')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('roles', 'Create'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
