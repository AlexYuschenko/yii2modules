<?php

use app\modules\user\Module;

/* @var $this yii\web\View */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
?>
<?= Yii::t('user', 'UK Hello {username}', ['username' => $user->username]); ?>,

<?= Yii::t('user', 'Follow the link below to reset your password:'); ?>

<?= $resetLink ?>
