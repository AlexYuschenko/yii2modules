<?php

namespace app\modules\user\widgets;

use yii\grid\DataColumn;
use yii\helpers\Html;
use Yii;

class AvatarColumn extends DataColumn
{
    public $path = '/uploads/avatars/';
    public $defaultAvatar = 'default_avatar.png';
    public $attributes = [];

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = $this->getDataCellValue($model, $key, $index);
        $avatar_path = Yii::$app->urlManager->baseUrl . $this->path;
        if (!empty(trim($model->first_name . ' ' . $model->last_name))) {
            $this->attributes['alt'] = trim($model->first_name . ' ' . $model->last_name);
            $this->attributes['title'] = trim($model->first_name . ' ' . $model->last_name);
        }
        $avatar = Html::img($avatar_path . ($value ? $value : $this->defaultAvatar), $this->attributes);
        return $value === null ? $this->grid->emptyCell : $avatar;
    }

}