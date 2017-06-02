<?php

namespace app\modules\hotel\models;

use Yii;
use app\modules\hotel\traits\ModuleTrait;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "rh_photos".
 *
 * @property integer $pid
 * @property integer $hotel_id
 * @property integer $room_id
 * @property string $uri
 * @property integer $main
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property RhRoom $room
 * @property RhHotel $hotel
 */
class Photos extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    public $photos;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rh_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hotel_id', 'room_id'], 'required'],
            [['hotel_id', 'room_id', 'main', 'created_at', 'updated_at'], 'integer'],
            [['uri'], 'string', 'max' => 255],
            [['pid', 'hotel_id', 'room_id'], 'unique', 'targetAttribute' => ['pid', 'hotel_id', 'room_id'], 'message' => 'The combination of Pid, Hotel ID and Room ID has already been taken.'],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Room::className(), 'targetAttribute' => ['room_id' => 'rid']],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'hid']],
            [['photos'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pid' => Yii::t('hotel', 'Pid'),
            'hotel_id' => Yii::t('hotel', 'Hotel ID'),
            'room_id' => Yii::t('hotel', 'Room ID'),
            'uri' => Yii::t('hotel', 'Uri'),
            'main' => Yii::t('hotel', 'Main'),
            'created_at' => Yii::t('hotel', 'Created At'),
            'updated_at' => Yii::t('hotel', 'Updated At'),
            'photos' => Yii::t('hotel', 'Photos'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function upload($dir)
    {
        if ($this->validate(['photos'])) {
            FileHelper::createDirectory('uploads/' . $dir, 0777);
            foreach ($this->photos as $photo) {
                $photo->saveAs('uploads/' . $dir . '/' . $photo->baseName . '.' . $photo->extension);
            }
            return true;
        } else {print_r($this->getErrors());
            return false;
        }
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
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['hid' => 'hotel_id']);
    }
}
