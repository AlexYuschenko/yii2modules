<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hotel\models\AttributesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('hotel', 'Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Hotels'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attributes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('hotel', 'Create Attributes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'aid',
                'options' => [
                    'style' => 'width:80px;',
                ],
            ],
            'name',
            [
                'attribute' => 'type',
                'filter' => $searchModel->attributeTypeArray,
                'value' => 'attributeTypeName',
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                },
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'created_at',
                    'dateFormat' => 'php:Y-m-d',
                    'options'    => [
                        'class' => 'form-control'
                    ]
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => [
                    'style' => 'width:60px;',
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
