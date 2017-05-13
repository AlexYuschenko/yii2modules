<?php
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('roles', 'Role manager');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Create Role', ['add-role'], ['class' => 'btn btn-success']) ?></p>

    <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => Yii::$app->authManager->getRoles(),
            'sort' => [
                'attributes' => ['name', 'description', 'createdAt'],
                'defaultOrder' => ['createdAt' => SORT_ASC]
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
            'label'     => 'Role'
        ],
        [
            'class'     => DataColumn::className(),
            'attribute' => 'description',
            'label'     => 'Description'
        ],
        // [
        //     'class'     => DataColumn::className(),
        //     'label'     => 'Allowed permissions',
        //     'format'    => ['html'],
        //     'value'     => function($data) {
        //         return implode('<br>',array_keys(ArrayHelper::map(Yii::$app->authManager->getPermissionsByRole($data->name), 'description', 'description')));
        //     }
        // ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['update-role', 'name' => $model->name]), [
                        'title' => Yii::t('roles', 'Update'),
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($url, $model) {
                    if (!in_array($model->name, $this->context->module->defaultRoles)) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-role','name' => $model->name]), [
                            'title' => Yii::t('roles', 'Delete'),
                            'data-confirm' => Yii::t('roles', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    }
                }
            ]
        ],
        ]
    ]);
?>
</div>