<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\user\models\User;
use app\modules\hotel\models\HotelType;
use app\modules\hotel\models\Country;
use kartik\time\TimePicker;

use app\widgets\gmaplocation\GMapLocationAssets;
use app\widgets\gmaplocation\GMapLocationWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\hotel\models\backend\Hotel */
/* @var $form yii\widgets\ActiveForm */
GMapLocationAssets::register($this);

$this->registerJs(new JsExpression('
(function ($) {
    "use strict";
    $("#hotel-country").on("select2:selecting", function () {
        $("#hotel-region").removeAttr("disabled");
        $("#hotel-city").removeAttr("disabled");
    });
    $("#hotel-city").on("select2:selecting", function () {
        $("#hotel-address").removeAttr("disabled");
    });
})(jQuery);
'));
?>
<div class="hotel-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'user_id')->dropDownlist(ArrayHelper::map(User::find()->all(), 'id', 'username')) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'stars')->dropDownlist([1, 2, 3, 4, 5], ['prompt' => Yii::t('hotel', 'No star')]) ?>

            <?= $form->field($model, 'hotel_type')->dropDownlist(ArrayHelper::map(HotelType::find()->orderBy(['created_at' => SORT_ASC])->all(), 'name', 'description')) ?>

            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'check_in')->widget(TimePicker::classname(), ['pluginOptions' => [
                        'minuteStep' => 10,
                        'showMeridian' => false,
                        'defaultTime' => '15:00 PM',
                        'showInputs' => false,
                    ]])->hint(Yii::t('hotel', 'e.g. 15:00')) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'check_out')->widget(TimePicker::classname(), ['pluginOptions' => [
                        'minuteStep' => 10,
                        'showMeridian' => false,
                        'defaultTime' => '12:00 AM',
                        'showInputs' => false,
                    ]])->hint(Yii::t('hotel', 'e.g. 12:00')) ?>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'country')
                ->widget(Select2::className(), [
                    'data'          => [],
                    'options'       => ['placeholder' => Yii::t('hotel', 'Select a Country ...')],
                    'pluginOptions' => [
                        'ajax' => [
                            'url'      => Url::to('/hotel/geo-api/countries'),
                            'dataType' => 'json',
                            'data'     => new JsExpression('function(params) {return {q:params.term}}')
                        ],
                    ],
                ])
            ?>

            <?= $form->field($model, 'region')
                ->widget(Select2::className(), [
                    'data'          => [],
                    'options'       => ['placeholder' => Yii::t('hotel', 'Select a Region ...')],
                    'disabled'      => true,
                    'pluginOptions' => [
                        'ajax' => [
                            'url'      => Url::to('/hotel/geo-api/regions'),
                            'dataType' => 'json',
                            'data'     => new JsExpression('function(params) {return {
                                q: params.term, 
                                country_id: $("#hotel-country").val()
                            }}')
                        ],
                    ],
                ])
            ?>

            <?= $form->field($model, 'city')
                ->widget(Select2::className(), [
                    'data'          => [],
                    'options'       => ['placeholder' => Yii::t('hotel', 'Select a City ...')],
                    'disabled'      => true,
                    'pluginOptions' => [
                        'ajax' => [
                            'url'      => Url::to('/hotel/geo-api/cities'),
                            'dataType' => 'json',
                            'data'     => new JsExpression('function(params) {return {
                                q:params.term,
                                country_id: $("#hotel-country").val(),
                                region_id: $("#hotel-region").val()
                            }}')
                        ],
                    ],
                ])
            ?>

            <?= $form->field($model, 'address')
                ->widget(GMapLocationWidget::className(), [
                    'attributeLatitude' => 'map_lat',
                    'attributeLongitude' => 'map_lng',
                    'attributeCountry' => 'country',
                    'attributeRegion' => 'region',
                    'attributeCity' => 'city',
                    'mapWrapper' => 'source-map',
                    'draggable' => true,
                    'textOptions' => [
                        'disabled' => true,
                        'class' => 'form-control',
                    ],
                ]);
            ?>
            <div id="source-map" style="width: 100%; height: 400px; margin-bottom: 20px;"></div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('hotel', 'Create') : Yii::t('hotel', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
