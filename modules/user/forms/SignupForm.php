<?php

namespace app\modules\user\forms;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\traits\ModuleTrait;

/**
 * Signup form
 */
class SignupForm extends Model
{
    use ModuleTrait;

    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'    => Yii::t('user', 'Username'),
            'email'       => Yii::t('user', 'Email'),
            'password'    => Yii::t('user', 'Password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('user', 'This username already exists.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('user', 'This Email already exists.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->save(false);

        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole('user');
        $auth->assign($authorRole, $user->getId());

        return $user;
    }
}