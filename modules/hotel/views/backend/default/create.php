<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\backend\Hotel */

$this->title = Yii::t('hotel', 'Create Hotel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'photos' => $photos,
    ]) ?>

</div>
