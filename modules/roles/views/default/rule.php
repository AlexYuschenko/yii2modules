<?php
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('roles', 'Rules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Add rule', ['add-rule'], ['class' => 'btn btn-success']) ?>
    </p>


<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' =>'name',
            'label'     => $searchModel->attributeLabels()['name'],
        ],
        [
            'attribute' =>'className',
            'label'     => $searchModel->attributeLabels()['className'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons'  => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['update-rule', 'name' => $model->name]), [
                        'title'     => Yii::t('roles', 'Update'),
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-rule','name' => $model->name]), [
                        'title'        => Yii::t('roles', 'Delete'),
                        'data-confirm' => Yii::t('roles', 'Are you sure you want to delete this item?'),
                        'data-method'  => 'post',
                        'data-pjax'    => '0',
                    ]);
                }
            ]
        ],
    ]
]);
?>
</div>