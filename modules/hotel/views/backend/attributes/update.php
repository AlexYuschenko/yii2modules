<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\Attributes */

$this->title = Yii::t('hotel', 'Update {modelClass}: ', [
    'modelClass' => 'Attributes',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->aid]];
$this->params['breadcrumbs'][] = Yii::t('hotel', 'Update');
?>
<div class="attributes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
