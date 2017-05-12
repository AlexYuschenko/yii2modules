<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = Yii::t('user', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('user', 'Update'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user', 'Change password'), ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'email',
        ],
    ]) ?>

</div>