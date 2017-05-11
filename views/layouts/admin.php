<?php

use app\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

/** @var \yii\web\Controller $context */
$context = $this->context;

if (isset($this->params['breadcrumbs'])) {
    $panelBreadcrumbs = [['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default/index']]];
    $breadcrumbs = $this->params['breadcrumbs'];
} else {
    $panelBreadcrumbs = [Yii::t('app', 'Admin')];
    $breadcrumbs = [];
}
?>
<?php $this->beginContent('@app/views/layouts/layout.php'); ?>

<?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'activateParents' => true,
        'items' => array_filter([
            ['label' => Yii::t('app', 'Home'), 'url' => ['/admin/default/index']],
            ['label' => Yii::t('app', 'Users'), 'url' => ['/admin/user/default/index'], 'active' => $context->module->id == 'user'],
            ['label' => Yii::t('app', 'Logout'), 'url' => ['/user/default/logout'], 'linkOptions' => ['data-method' => 'post']]
        ]),
    ]);
    NavBar::end();
?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => ArrayHelper::merge($panelBreadcrumbs, $breadcrumbs),
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

<?php $this->endContent(); ?>