<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\user\models\User;
use app\modules\hotel\models\HotelType;
use app\modules\hotel\models\Country;

use app\widgets\gmaplocation\GMapLocationAssets;
use app\widgets\gmaplocation\GMapLocationWidget;

use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\file\FileInput;

use yii\httpclient\Client;

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

            <?php
                $key = 'hotels';
                $preview = [];
                $initialPreviewConfig = [];
                if (!$model->isNewRecord) {
                    // foreach (json_decode($model->files) as $fid => $file) {
                    //     $preview[] = Html::img('/uploads/' . $model->id . '/' . $file, [
                    //         'class' => 'file-preview-image',
                    //         'alt' => '',
                    //         'title' => $file
                    //     ]);
                    //     $url = Url::to(['projects/file-delete', 'id' => $key, 'key' => $fid]);
                    //     $initialPreviewConfig[] = ['url' => $url, 'caption' => $file];
                    // }
                }
            ?>
            <?= $form->field($photos, 'photos[]')->widget(FileInput::classname(), [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    // 'previewFileType' => 'any',
                    'uploadUrl' => Url::to(['photo-upload']),
                    'deleteUrl' => Url::to(['/hotel/photo-delete']),
                    'uploadExtraData' => ['folderId' => $key],
                    'uploadAsync' => false,
                    'maxFileCount' => 10,
                    'minFileCount' => 1,
                    'validateInitialCount' => true,
                    'overwriteInitial' => false,
                    //'autoReplace' => true,
                    'browseClass' => 'btn btn-primary btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    //'browseLabel' => Yii::t('app', 'Select file(s)'),
                    'showUpload' => false,
                    'showRemove' => false,
                    'showCaption' => false,
                    'showBrowse' => false,
                    'showPreview' => true,
                    'initialPreview' => $preview,
                    'initialPreviewShowDelete' => true,
                    'initialPreviewConfig' => $initialPreviewConfig,
                    'dropZoneEnabled' => true,
                    'browseOnZoneClick' => true,
                    'dropZoneClickTitle' => Yii::t('hotel', ' or click to select'),
                    'fileActionSettings' => [
                        // 'uploadClass' => 'hide',
                        'showDrag' => true,
                    ],
                    'layoutTemplates' => [
                        'footer' => '<div class="file-thumbnail-footer">
                                ' . Html::activeHiddenInput($model, 'photos[]', ['value' => '{caption}']) . '
                                <div class="file-footer-caption" title="{caption}">{caption}<br></div>
                                {size} {progress}
                                <div class="file-actions">
                                    <div class="file-footer-buttons">
                                         {actions}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>',
                    ],
                ],
                'pluginEvents' => [
                    'filepredelete' => "function(event, key) {
                        return (!confirm('" . Yii::t('hotel', 'Are you sure you want to delete ?') . "')); 
                    }",
                    'filedelete' => "function(event, key) { console.log('File is delete'); }",
                    'filebatchselected' => "function(event, files) {
                        console.log(files);
                        // trigger upload method immediately after files are selected
                        $(this).fileinput('upload');
                    }",
                    'filebatchuploadsuccess' => "function(event, data, previewId, index) {
                        var form = data.form, files = data.files, extra = data.extra,
                        response = data.response, reader = data.reader,
                        filenames = response.filenames,
                        field = '#" . Html::getInputId($model, 'photos[]') . "';
                        console.log(field);
                        console.log('File batch upload success');
                        console.log(data);
                        $.each(filenames, function(index, value){
                            $(field).last().before($(field).clone().val(value));
                        });
                    }",
                ]
            ]); ?>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'country')
                ->widget(Select2::className(), [
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
                    // 'data'          => [],
                    'options'       => ['placeholder' => Yii::t('hotel', 'Select a Region ...')],
                    'disabled'      => empty($model->country),
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
                    // 'data'          => [],
                    'options'       => ['placeholder' => Yii::t('hotel', 'Select a City ...')],
                    'disabled'      => empty($model->country),
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
                    'zoom' => !empty($model->address) ? 14 : 2,
                    'textOptions' => [
                        'disabled' => empty($model->city),
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
