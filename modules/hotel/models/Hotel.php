<?php

namespace app\modules\hotel\models;

use Yii;
use app\modules\user\models\User;
use app\modules\hotel\models\HotelTyle;
use app\modules\hotel\models\AttributesAssignment;
use app\modules\hotel\models\Room;
// use app\modules\hotel\models\Country;
use app\modules\hotel\traits\ModuleTrait;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rh_hotel".
 *
 * @property integer $hid
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property integer $stars
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $address
 * @property string $map_lat
 * @property string $map_lng
 * @property string $hotel_type
 * @property string $check_in
 * @property string $check_out
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AttributesAssignment[] $AttributesAssignments
 * @property HotelType $hotelType
 * @property User $user 
 * @property Photos[] $Photos 
 * @property Room[] $Rooms
 */
class Hotel extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rh_hotel';
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
            [['name', 'country', 'region', 'city', 'address', 'map_lat', 'map_lng', 'check_in', 'check_out'], 'required'],
            [['user_id', 'stars', 'created_at', 'updated_at'], 'integer'],
            [['region', 'city', 'address', 'description'], 'string'],
            [['map_lat', 'map_lng'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['check_in', 'check_out'], 'string', 'max' => 10],
            [['hotel_type'], 'string', 'max' => 100],
            [['hotel_type'], 'exist', 'skipOnError' => true, 'targetClass' => HotelType::className(), 'targetAttribute' => ['hotel_type' => 'name']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hid' => Yii::t('hotel', 'Hotel ID'),
            'user_id' => Yii::t('hotel', 'User'),
            'name' => Yii::t('hotel', 'Name'),
            'description' => Yii::t('hotel', 'Description'),
            'stars' => Yii::t('hotel', 'Stars'),
            'country' => Yii::t('hotel', 'Country'),
            'region' => Yii::t('hotel', 'Region'),
            'city' => Yii::t('hotel', 'City'),
            'address' => Yii::t('hotel', 'Address'),
            'map_lat' => Yii::t('hotel', 'Map Lat'),
            'map_lng' => Yii::t('hotel', 'Map Lng'),
            'hotel_type' => Yii::t('hotel', 'Hotel Type'),
            'check_in' => Yii::t('hotel', 'Checkin Time'),
            'check_out' => Yii::t('hotel', 'Checkout Time'),
            'created_at' => Yii::t('hotel', 'Created At'),
            'updated_at' => Yii::t('hotel', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesAssignments()
    {
        return $this->hasMany(AttributesAssignment::className(), ['hotel_id' => 'hid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotelType()
    {
        return $this->hasOne(HotelType::className(), ['name' => 'hotel_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['hotel_id' => 'hid']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['hotel_id' => 'hid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['code' => 'country']);
    }
}
