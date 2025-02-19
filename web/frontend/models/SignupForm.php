<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = 9;
            $user->generateEmailVerificationToken();

            if ($user->save()) {
                $auth = Yii::$app->authManager;
                $buyerRole = $auth->getRole('buyer');

                if ($buyerRole) {
                    $auth->assign($buyerRole, $user->id);
                }

                $this->sendVerificationEmail($user);

                return $user;
            }
        }

        return null;
    }

    private function sendVerificationEmail($user)
    {
        $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify', 'token' => $user->verification_token]);

        return Yii::$app->mailer->compose()
            ->setFrom('automail.cardhub@gmail.com')
            ->setTo($user->email)
            ->setSubject('Activate your account')
            ->setTextBody("Click the link below to activate your account:\n" . $verifyLink)
            ->send();
    }
}
