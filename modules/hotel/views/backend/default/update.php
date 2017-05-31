<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\backend\Hotel */

$this->title = Yii::t('hotel', 'Update {modelClass}: ', [
    'modelClass' => 'Hotel',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->hid]];
$this->params['breadcrumbs'][] = Yii::t('hotel', 'Update');
?>
<div class="hotel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
