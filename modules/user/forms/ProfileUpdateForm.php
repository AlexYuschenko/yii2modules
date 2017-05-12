<?php

namespace app\modules\user\forms;

use Yii;
use app\modules\user\models\User;
use yii\base\Model;
use yii\db\ActiveQuery;
use app\modules\user\traits\ModuleTrait;

class ProfileUpdateForm extends Model
{
    use ModuleTrait;

    public $email;
    public $first_name;
    public $last_name;
    public $avatar;
    public $avatarImage;

    /**
     * @var User
     */
    private $_user;

    /**
     * @param User $user
     * @param array $config
     */
    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->email = $user->email;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->avatar = $user->avatar;
        parent::__construct($config);
    }

    /** 
    * @inheritdoc 
    */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => Yii::t('user', 'This Email already exists.'),
                'filter' => function (ActiveQuery $query) {
                    $query->andWhere(['<>', 'id', $this->_user->id]);
                },
            ],
            ['email', 'string', 'max' => 255],

            ['first_name', 'string'],

            ['last_name', 'string'],

            [['avatarImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @return bool
     */
    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->avatar = $this->avatar;
            $user->email = $this->email;
            return $user->save();
        } else {
            return false;
        }
    }
} 