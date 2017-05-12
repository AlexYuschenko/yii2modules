<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\user\widgets\AvatarColumn;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\backend\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'avatar',
                'format' => 'html',
                'value' => Html::img(Yii::$app->urlManager->baseUrl . '/uploads/avatars/' . ($model->avatar ? $model->avatar : 'default_avatar.png'), [
                        'alt'    => $model->first_name . ' ' . $model->last_name,
                        'title'  => $model->first_name . ' ' . $model->last_name,
                        'class'  => 'file-preview-image img-circle',
                        'width'  => '50',
                        'height' => '50'
                    ]),
            ],
            'username',
            'first_name',
            'last_name',
            'email:email',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            [
                'attribute' => 'role',
                'format' => 'raw',
                'value' => Html::ul($model->userRole),
            ],
        ],
    ]) ?>

</div>
