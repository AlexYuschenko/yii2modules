<?php

use yii\helpers\Html;
use app\modules\user\Module;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?= Yii::t('user', 'UK Hello {username}', ['username' => $user->username]); ?>,</p>

    <p><?= Yii::t('user', 'Follow the link below to reset your password:'); ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
