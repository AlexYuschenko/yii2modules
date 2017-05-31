<?php

namespace app\modules\hotel\models;

use Yii;
use app\modules\hotel\traits\ModuleTrait;

/**
 * This is the model class for table "rh_room_type".
 *
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property RhRoom[] $rhRooms
 */
class RoomType extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rh_room_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            ['name', 'match', 'pattern' => '#^[\w_-]+$#is', 'message' => Yii::t('hotel', 'Name can only contain alphanumeric characters, underscores and dashes.')],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('hotel', 'Name'),
            'description' => Yii::t('hotel', 'Description'),
            'created_at' => Yii::t('hotel', 'Created At'),
            'updated_at' => Yii::t('hotel', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['room_type' => 'name']);
    }
}
