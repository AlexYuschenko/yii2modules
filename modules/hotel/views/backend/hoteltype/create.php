<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\HotelType */

$this->title = Yii::t('hotel', 'Create Hotel Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Hotel Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
