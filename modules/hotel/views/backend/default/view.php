<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\backend\Hotel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('hotel', 'Update'), ['update', 'id' => $model->hid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('hotel', 'Delete'), ['delete', 'id' => $model->hid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('hotel', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'hid',
            'user_id',
            'name',
            'description:ntext',
            'stars',
            'country',
            'city',
            'address',
            'map_lat',
            'map_lng',
            'hotel_type',
            'check_in',
            'check_out',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
