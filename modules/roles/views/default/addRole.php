<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Links */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('roles', 'Create Role');
$this->params['breadcrumbs'][] = ['label' => 'Role manager', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="links-form">
        <?php
        if (!empty($error)) {
            ?>
            <div class="error-summary">
                <?php
                echo implode('<br>', $error);
                ?>
            </div>
        <?php
        }
        ?>
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= Html::label('Role name'); ?>
            <?= Html::textInput('name'); ?>
            * only latin letter, numbers and _ -
        </div>

        <div class="form-group">
            <?= Html::label('Description'); ?>
            <?= Html::textInput('description'); ?>
        </div>

        <div class="form-group">
            <?= Html::label('Permissions'); ?>
            <?= Html::checkboxList('permissions', null, $permissions, ['separator' => '<br>']); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>