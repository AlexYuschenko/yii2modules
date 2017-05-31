<?php

namespace app\modules\hotel\models;

use Yii;
use app\modules\hotel\traits\ModuleTrait;

/**
 * This is the model class for table "country".
 *
 * @property string $code
 * @property string $name
 * @property string $isoNumeric
 * @property integer $geonameId
 */
class Country extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['geonameId'], 'integer'],
            [['code'], 'string', 'max' => 4],
            [['name', 'isoNumeric'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('hotel', 'Code'),
            'name' => Yii::t('hotel', 'Country'),
            'isoNumeric' => Yii::t('hotel', 'Iso Numeric'),
            'geonameId' => Yii::t('hotel', 'Geoname ID'),
        ];
    }
}
