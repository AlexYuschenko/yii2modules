<?php
namespace app\widgets\gmaplocation;

use Yii;
use yii\web\AssetBundle;

/**
 * GMapLocationWidget assets class
 */
class GMapLocationAssets extends AssetBundle
{
    public $sourcePath = '@app/widgets/gmaplocation/js';
    public $css = [];
    public $js = [
        'gmap-location.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * Google API Key
     *
     * @var string
     */
    public $key;
    public $language = 'en';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->js[] = 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=' . $this->language . '&key=' . $this->key;
    }
}
