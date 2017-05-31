<?php

namespace app\modules\hotel\models;

use Yii;
use app\modules\hotel\traits\ModuleTrait;

/**
 * This is the model class for table "rh_attributes_assignment".
 *
 * @property integer $attribute_id
 * @property integer $hotel_id
 * @property integer $room_id
 * @property integer $created_at
 *
 * @property RhRoom $room
 * @property RhAttributes $attribute
 * @property RhHotel $hotel
 */
class AttributesAssignment extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rh_attributes_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'hotel_id', 'room_id'], 'required'],
            [['attribute_id', 'hotel_id', 'room_id', 'created_at'], 'integer'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['room_id' => 'rid']],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attributes::className(), 'targetAttribute' => ['attribute_id' => 'aid']],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'hid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => Yii::t('hotel', 'Attribute ID'),
            'hotel_id' => Yii::t('hotel', 'Hotel ID'),
            'room_id' => Yii::t('hotel', 'Room ID'),
            'created_at' => Yii::t('hotel', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::className(), ['rid' => 'room_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute()
    {
        return $this->hasOne(Attributes::className(), ['aid' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['hid' => 'hotel_id']);
    }
}
