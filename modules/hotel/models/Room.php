<?php

namespace app\modules\hotel\models;

use Yii;
use app\modules\hotel\traits\ModuleTrait;

/**
 * This is the model class for table "rh_room".
 *
 * @property integer $rid
 * @property integer $hotel_id
 * @property string $name
 * @property string $description
 * @property integer $floor
 * @property integer $room_number
 * @property string $square
 * @property string $price
 * @property string $room_type
 * @property integer $beds
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property RhAttributesAssignment[] $rhAttributesAssignments
 * @property RhHotel $hotel
 */
class Room extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rh_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hotel_id', 'name', 'floor', 'room_number', 'square', 'price', 'beds'], 'required'],
            [['hotel_id', 'floor', 'room_number', 'beds', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['square'], 'string', 'max' => 64],
            [['room_type'], 'string', 'max' => 100],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'hid']],
            [['room_type'], 'exist', 'skipOnError' => true, 'targetClass' => RoomType::className(), 'targetAttribute' => ['room_type' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rid' => Yii::t('hotel', 'Room ID'),
            'hotel_id' => Yii::t('hotel', 'Hotel ID'),
            'name' => Yii::t('hotel', 'Name'),
            'description' => Yii::t('hotel', 'Description'),
            'floor' => Yii::t('hotel', 'Floor'),
            'room_number' => Yii::t('hotel', 'Room Number'),
            'square' => Yii::t('hotel', 'Square'),
            'price' => Yii::t('hotel', 'Price'),
            'room_type' => Yii::t('hotel', 'Room Type'),
            'beds' => Yii::t('hotel', 'Beds'),
            'created_at' => Yii::t('hotel', 'Created At'),
            'updated_at' => Yii::t('hotel', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesAssignments()
    {
        return $this->hasMany(AttributesAssignment::className(), ['room_id' => 'rid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['hid' => 'hotel_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRoomType()
    {
        return $this->hasOne(RoomType::className(), ['name' => 'room_type']);
    }
}
