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
            <?= Html::label('Description'); ?>
            <?= Html::textInput('description'); ?>
        </div>

        <div class="form-group">
            <?= Html::label('Pemission'); ?>
            <?= Html::textInput('name'); ?>
            * Format module/controller/action<br>
            site/article - access to page site/article<br>
            site - access to all actions at site controller
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
