<?php

namespace app\modules\site\forms;

use Yii;
use yii\base\Model;
use app\modules\site\traits\ModuleTrait;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    use ModuleTrait;

    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'captchaAction' => '/site/contact/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('site', 'Verification Code'),
            'name' => Yii::t('site', 'Name'),
            'email' => Yii::t('site', 'E-mail'),
            'subject' => Yii::t('site', 'Subject'),
            'body' => Yii::t('site', 'Body'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
