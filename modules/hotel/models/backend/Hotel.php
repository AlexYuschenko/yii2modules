<?php

namespace app\modules\hotel\models\backend;

use Yii;
use app\modules\hotel\traits\ModuleTrait;

/**
 * This is the model class for table "rh_hotel".
 *
 * @property integer $hid
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property integer $stars
 * @property integer $country
 * @property integer $city
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
class Hotel extends \app\modules\hotel\models\Hotel
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    // public function rules()
    // {
    //     return [
    //         [['user_id', 'name', 'country', 'city', 'address', 'map_lat', 'map_lng', 'type', 'check_in', 'check_out'], 'required'],
    //         [['user_id', 'stars', 'country', 'city', 'type', 'created_at', 'updated_at'], 'integer'],
    //         [['description'], 'string'],
    //         [['map_lat', 'map_lng'], 'number'],
    //         [['name', 'address'], 'string', 'max' => 255],
    //         [['check_in', 'check_out'], 'string', 'max' => 10],
    //         [['hid'], 'exist', 'skipOnError' => true, 'targetClass' => RhRoom::className(), 'targetAttribute' => ['hid' => 'hotel_id']],
    //     ];
    // }
}
