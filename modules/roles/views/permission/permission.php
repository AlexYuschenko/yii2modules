<?php
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('roles', 'Permissions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('roles', 'Role manager'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permissions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Create Permission', ['add-permission'], ['class' => 'btn btn-success']) ?></p>

    <?php
        $permissions = Yii::$app->authManager->getPermissions();
        $roles = Yii::$app->authManager->getRoles();
        $attributes = ['name', 'description', 'createdAt'];
        $data = array();
        foreach ($permissions as $key => $permission) {
            if (!isset($data[$key])) {
                $data[$key] = new stdClass();
            }
            $data[$key]->name = $permission->name;
            $data[$key]->description = $permission->description;
            $data[$key]->createdAt = $permission->createdAt;
            foreach ($roles as $role => $roleData) {
                $data[$key]->{'roleName_' . $role} = array_keys(Yii::$app->authManager->getPermissionsByRole($roleData->name));
                $attributes[] = 'roleName_' . $role;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['name', 'createdAt'],
                'defaultOrder' => ['createdAt' => SORT_ASC]
            ],
        ]);
    ?>

<?php
$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'class' => DataColumn::className(),
        'attribute' => 'name',
        'label' => 'Permission',
        'format' => 'html',
        'value' => function($model) {
            return $model->name . '<div class="description">' . $model->description . '</div>';
        }
    ],
];

foreach ($roles as $role => $roleData) {
    $columns[] = [
        'class' => DataColumn::className(),
        'attribute' => 'roleName_' . $roleData->name,
        'label' => $roleData->name,
        'format' => 'html',
        'value' => function($model, $key, $index, $column) {
            if ($column->label == 'admin' && in_array($key, $this->context->module->defaultPermissions)) {
                return in_array($key, $model->{$column->attribute}) ? Yii::t('roles', 'Yes') : Yii::t('roles', 'No');
            }
            if (in_array($key, $model->{$column->attribute})) {
                return Html::a(Yii::t('roles', 'Yes'), ['remove-role-permission', 'role' => $column->label, 'permission' => $key]);
            } else {
                return Html::a(Yii::t('roles', 'No'), ['add-role-permission', 'role' => $column->label, 'permission' => $key]);;
            }
        }
    ];
}

$columns[] = [
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
            if (!in_array($model->name, $this->context->module->defaultPermissions)) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-permission','name' => $model->name]), [
                    'title' => Yii::t('roles', 'Delete'),
                    'data-confirm' => Yii::t('roles', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);
            }
        }
    ]
];

?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns
]);
?>
</div>