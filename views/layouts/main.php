<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;

?>
<?php $this->beginContent('@app/views/layouts/layout.php'); ?>
<?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top header',
        ],
    ]);

    $menuItems[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/site/default/index']];
    $menuItems[] = ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact/index']];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Signup'), 'url' => ['/user/default/signup']];
        $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/user/default/login']];
    } else {
        $menuItems[] = [
            'label' => Yii::t('app', 'My account') . ' (' . Yii::$app->user->identity->username . ')',
            'url' => ['/user/default/' . Yii::$app->user->id]
        ];
        $menuItems[] = [
            'label' => Yii::t('app', 'Logout'),
            'url' => ['/user/default/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endContent(); ?>