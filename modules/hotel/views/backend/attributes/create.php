<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\Attributes */

$this->title = Yii::t('hotel', 'Create Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hotel', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
