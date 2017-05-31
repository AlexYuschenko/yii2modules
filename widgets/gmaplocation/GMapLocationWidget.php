<?php
namespace app\widgets\gmaplocation;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Widget for select map location. It\'s render google map and input field for type a map location.
 * Latitude and longitude are provided in the attributes $attributeLatitude and $attributeLongitude.
 * Base usage:
 *
 * $form->field($model, 'location')->widget(\app\widgets\gmaplocation\GMapLocationWidget::className(), [
 *     'attributeLatitude' => 'latitude',
 *     'attributeLongitude' => 'longitude',
 * ]);
 *
 * or
 *
 * \app\widgets\gmaplocation\GMapLocationWidget::widget([
 *     'model' => $model,
 *     'attribute' => 'location',
 *     'attributeLatitude' => 'latitude',
 *     'attributeLongitude' => 'longitude',
 * ]);
 *
 * @property Model $model base yii2 model or ActiveRecord object
 * @property string $attribute attribute to write map location
 * @property string $attributeLatitude attribute to write location latitude
 * @property string $attributeLongitude attribute to write location longitude
 * @property callable|null $renderWidgetMap custom function to render map
 */
class GMapLocationWidget extends InputWidget
{
    /**
     * @var string latitude attribute name
     */
    public $attributeLatitude;

    /**
     * @var string longitude attribute name
     */
    public $attributeLongitude;

    /**
     * @var string country attribute name
     */
    public $attributeCountry;

    /**
     * @var string region attribute name
     */
    public $attributeRegion;

    /**
     * @var string city attribute name
     */
    public $attributeCity;

    /**
     * @var boolean marker draggable option
     */
    public $draggable = false;

    /**
     * @var id for map wrapper div
     */
    public $mapWrapper = 'map';

    /**
     * @var array options for attribute text input
     */
    public $textOptions = ['class' => 'form-control'];

    /**
     * @var array JavaScript options
     */
    public $jsOptions = [];

    /**
     * @var callable function for custom map render
     */
    public $renderWidgetMap;

    /**
     * Run widget
     */
    public function run()
    {
        parent::run();

        // getting inputs ids
        $address = Html::getInputId($this->model, $this->attribute);
        $latitude = Html::getInputId($this->model, $this->attributeLatitude);
        $longitude = Html::getInputId($this->model, $this->attributeLongitude);

        if (isset($this->jsOptions['componentRestrictions'])) {
            $this->jsOptions['componentRestrictions'] = '#' . Html::getInputId($this->model, $this->jsOptions['componentRestrictions']);
        }

        if (!empty($this->attributeCountry)) {
            $this->jsOptions['country'] = '#' . Html::getInputId($this->model, $this->attributeCountry);
        }

        if (!empty($this->attributeRegion)) {
            $this->jsOptions['region'] = '#' . Html::getInputId($this->model, $this->attributeRegion);
        }

        if (!empty($this->attributeCity)) {
            $this->jsOptions['city'] = '#' . Html::getInputId($this->model, $this->attributeCity);
        }

        $jsOptions = ArrayHelper::merge($this->jsOptions, [
            'field' => $this->attribute,
            'model' => strtolower(\yii\helpers\StringHelper::basename(get_class($this->model))),
            'address'           => '#' . $address,
            'latitude'          => '#' . $latitude,
            'longitude'         => '#' . $longitude,
            'draggable'         => $this->draggable,
        ]);
        // message about not founded addess
        if (!isset($jsOptions['addressNotFound'])) {
            $hasMainCategory = isset(Yii::$app->i18n->translations['*']) || isset(Yii::$app->i18n->translations['app']);
            $jsOptions['addressNotFound'] = $hasMainCategory ? Yii::t('app', 'Address not found') : 'Address not found';
        }
        $this->view->registerJs(new JsExpression('
            $(document).ready(function() {
                $(\'#' . $this->mapWrapper . '\').selectLocation(' . Json::encode($jsOptions) . ');
            });
        '));
        $mapHtml = Html::activeHiddenInput($this->model, $this->attributeLatitude);
        $mapHtml .= Html::activeHiddenInput($this->model, $this->attributeLongitude);

        if (is_callable($this->renderWidgetMap)) {
            return call_user_func_array($this->renderWidgetMap, [$mapHtml]);
        }

        return Html::activeInput('text', $this->model, $this->attribute, $this->textOptions) . $mapHtml;
    }
}
