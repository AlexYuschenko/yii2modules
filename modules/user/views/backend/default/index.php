<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\modules\user\widgets\AvatarColumn;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\user\models\backend\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('user', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
                    'style' => 'width:80px;',
                ],
            ],
            [
                'class' => AvatarColumn::className(),
                'attribute' => 'avatar',
                'attributes' => [
                    'class'  => 'file-preview-image img-circle',
                    'width'  => '40',
                    'height' => '40'
                ],
            ],
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a(Html::encode($model->username), Url::to(['/admin/user/' . $model->id]));
                },
            ],
            'first_name',
            'last_name',
            [
                'attribute' => 'role',
                'value' => 'userRole',
                'filter' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
                'options' => [
                    'style' => 'width:150px;',
                ],
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
                'attribute' => 'status',
                'filter' => $searchModel->statusesArray,
                'value' => 'statusName',
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
<?php Pjax::end(); ?></div>
