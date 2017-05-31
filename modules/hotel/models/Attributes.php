<?php

namespace app\modules\hotel\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use app\modules\hotel\traits\ModuleTrait;

/**
 * This is the model class for table "rh_attributes".
 *
 * @property integer $aid
 * @property integer $type
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property RhAttributesAssignment[] $rhAttributesAssignments
 */
class Attributes extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    const HOTEL_TYPE = 1;
    const ROOM_TYPE = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rh_attributes';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::getAttributeTypeArray())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => Yii::t('hotel', 'Attribute ID'),
            'name' => Yii::t('hotel', 'Name'),
            'type' => Yii::t('hotel', 'Type'),
            'created_at' => Yii::t('hotel', 'Created At'),
            'updated_at' => Yii::t('hotel', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAttributeTypeName()
    {
        return ArrayHelper::getValue(self::getAttributeTypeArray(), $this->type);
    }

    /**
     * @inheritdoc
     */
    public static function getAttributeTypeArray()
    {
        return [
            self::HOTEL_TYPE => Yii::t('user', 'Hotel attribute'),
            self::ROOM_TYPE => Yii::t('user', 'Room attribute'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesAssignments()
    {
        return $this->hasMany(AttributesAssignment::className(), ['attribute_id' => 'aid']);
    }
}
