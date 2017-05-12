<?php
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('roles', 'Permissions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Create Permission', ['add-permission'], ['class' => 'btn btn-success']) ?>
    </p>
<?php
$dataProvider = new ArrayDataProvider([
    'allModels' => Yii::$app->authManager->getPermissions(),
    'sort' => [
        'attributes' => ['name', 'description'],
    ],
    'pagination' => [
        'pageSize' => 10,
    ],
 ]);
?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class'     => DataColumn::className(),
            'attribute' => 'name',
            'label'     => 'Permission'
        ],
        [
            'class'     => DataColumn::className(),
            'attribute' => 'description',
            'label'     => 'Description'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['update-permission', 'name' => $model->name]), [
                        'title' => Yii::t('roles', 'Update'),
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-permission','name' => $model->name]), [
                        'title' => Yii::t('roles', 'Delete'),
                        'data-confirm' => Yii::t('roles', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ]);
                }
            ]
        ],
        ]
    ]);
?>
</div>