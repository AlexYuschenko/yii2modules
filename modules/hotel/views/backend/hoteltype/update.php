<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\HotelType */

$this->title = Yii::t('hotel', 'Update {modelClass}: ', [
    'modelClass' => 'Hotel Type',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Hotel Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('hotel', 'Update');
?>
<div class="hotel-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
