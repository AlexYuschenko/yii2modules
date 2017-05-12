<?php

namespace app\modules\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use app\modules\user\traits\ModuleTrait;

class User extends ActiveRecord implements IdentityInterface
{
    use ModuleTrait;

    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;

    public $avatarImage;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'    => Yii::t('user', 'Username'),
            'first_name'  => Yii::t('user', 'First Name'),
            'last_name'   => Yii::t('user', 'Last Name'),
            'email'       => Yii::t('user', 'Email'),
            'password'    => Yii::t('user', 'Password'),
            'role'        => Yii::t('user', 'Role'),
            'created_at'  => Yii::t('user', 'Registration Date'),
            'avatar'      => Yii::t('user', 'Avatar'),
            'avatarImage' => Yii::t('user', 'Avatar'),
            'status'      => Yii::t('user', 'Status'),
            'fullName'    => Yii::t('user', 'Full Name'),
        ];
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
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('user', 'This username already exists.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('user', 'This Email already exists.')],
            ['email', 'string', 'max' => 255],

            ['status', 'integer'],
            ['status', 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],

            [['avatarImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     * @inheritdoc
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => Yii::t('user', 'Blocked'),
            self::STATUS_ACTIVE => Yii::t('user', 'Active'),
            self::STATUS_WAIT => Yii::t('user', 'Waits of verify'),
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token, $timeout)
    {
        if (!static::isPasswordResetTokenValid($token, $timeout)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token, $timeout)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        return $timestamp + $timeout >= time();
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getRoleTypes()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    /**
     * @inheritdoc
     */
    public function getUserRole($id = null)
    {
        if (null === $id) {
            $Ridentity = Yii::$app->authManager->getRolesByUser($this->id);
        } else {
            $Ridentity = Yii::$app->authManager->getRolesByUser($id);
        }
        if ($Ridentity) {
            foreach ($Ridentity as $item) {
               $role[$item->name] = $item->name;
            }
        } else {
            $role = [];
        }
        return $role;
    }

    /**
     * Finds all users by assignment role
     *
     * @param  \yii\rbac\Role $role
     * @return static|null
     */
    // public static function findByRole($roleName)
    // {
    //     $role = Yii::$app->authManager->getRole($roleName);
    //     return static::find()
    //         ->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = id')
    //         ->where(['auth_assignment.item_name' => $role->name])
    //         ->all();
    // }
}