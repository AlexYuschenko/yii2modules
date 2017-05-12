<?php

namespace app\modules\user\models\backend;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\user\traits\ModuleTrait;

class User extends \app\modules\user\models\User
{
    use ModuleTrait;

    const SCENARIO_ADMIN_CREATE = 'adminCreate';
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';

    public $newPassword;
    public $newPasswordRepeat;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['newPassword', 'newPasswordRepeat'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
            ['role', 'required', 'on' => [self::SCENARIO_ADMIN_CREATE, self::SCENARIO_ADMIN_UPDATE]],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['username', 'email', 'first_name', 'last_name', 'status', 'role', 'newPassword', 'newPasswordRepeat'];
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['username', 'email', 'first_name', 'last_name', 'status', 'role', 'newPassword', 'newPasswordRepeat'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'newPassword' => Yii::t('user', 'New password'),
            'newPasswordRepeat' => Yii::t('user', 'Repeat new password'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->newPassword)) {
                $this->setPassword($this->newPassword);
            }
            return true;
        }
        return false;
    }
} 