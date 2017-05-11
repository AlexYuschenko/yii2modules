<?php

namespace app\modules\user\forms;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\traits\ModuleTrait;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    use ModuleTrait;

    public $email;

    private $_timeout;

    /**
     * PasswordResetRequestForm constructor.
     * @param integer $timeout
     * @param array $config
     */
    public function __construct($timeout, $config = [])
    {
        $this->_timeout = $timeout;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('user', 'There is no user with this email address.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token, $this->_timeout)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        return Yii::$app
            ->mailer
            ->compose(
                [
                    'html' => '@app/modules/user/mails/passwordResetToken-html',
                    'text' => '@app/modules/user/mails/passwordResetToken-text',
                    //'html' => '/passwordResetToken-html',
                    //'text' => '/passwordResetToken-text',
                ],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject(Yii::t('user', 'Password reset for {name}', ['name' => Yii::$app->name]))
            ->send();
    }
}
