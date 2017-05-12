<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Links */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('roles', 'Update role: {role}', ['role' => $role->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="links-form">

        <?php if (!empty($error)) { ?>
            <div class="error-summary">
                <?php echo implode('<br>', $error); ?>
            </div>
        <?php } ?>

        <?php $form = ActiveForm::begin();?>

        <div class="form-group">
            <?php if (in_array($role->name, $this->context->module->defaultRoles)) {
                $role_options = ['readonly' => 'readonly'];
            } else {
                $role_options = [];
            } ?>
            <?= Html::label('Role name'); ?>
            <?= Html::textInput('name', $role->name, $role_options); ?>
        </div>

        <div class="form-group">
            <?= Html::label('Description'); ?>
            <?= Html::textInput('description', $role->description); ?>
        </div>

        <div class="form-group">
            <?= Html::label('Permissions'); ?>
            <?= Html::checkboxList('permissions', $role_permit, $permissions, ['separator' => '<br>']); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
