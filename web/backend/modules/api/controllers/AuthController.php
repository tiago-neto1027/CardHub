<?php

namespace backend\modules\api\controllers;

use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'except' => ['login', 'signup'],
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $authHeader = Yii::$app->request->headers->get('Authorization');

        if (empty($authHeader)) {
            throw new BadRequestHttpException('Authorization header is required.');
        }
        
        if (strpos($authHeader, 'Basic ') === false) {
            throw new BadRequestHttpException('Invalid Authorization header format.');
        }

        $base64Credentials = substr($authHeader, 6);
        $credentials = base64_decode($base64Credentials);

        $credentialsArray = explode(':', $credentials);

        if (count($credentialsArray) !== 2) {
            throw new BadRequestHttpException('Invalid Authorization header format.');
        }

        $username = $credentialsArray[0];
        $password = $credentialsArray[1];

        if (empty($username) || empty($password)) {
            throw new BadRequestHttpException('Username and password are required.');
        }

        $user = User::findByUsername($username);

        if ($user === null || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Invalid username or password.');
        }

        Yii::$app->user->login($user);

        return [
            'status' => 200,
            'message' => 'Login successful',
        ];
    }

    public function actionSignup(){
        $data = Yii::$app->request->post();

        //Checks if there are missing parameters
        if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
            throw new BadRequestHttpException('Username, password, and email are required.');
        }

        //Checks if username or email are taken
        if (User::findByUsername($data['username']) !== null) {
            throw new ConflictHttpException('Username is already taken.');
        }
        if (User::findByEmail($data['email']) !== null) {
            throw new ConflictHttpException('Email is already taken.');
        }

        //Creates the new user
        $user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->setPassword($data['password']);
        $user->generateAuthKey();
        $user->status = User::STATUS_INACTIVE;
        $user->generateEmailVerificationToken();

        //Saves the user and sends email
        if ($user->save()) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole('buyer');

            if ($role) {
                $auth->assign($role, $user->id);
            } else {
                throw new \Exception('Error: Role not found.');
            }

            if ($this->sendVerificationEmail($user)) {
                return [
                    'status' => 201,
                    'message' => 'User created successfully. Please verify your email.',
                ];
            } else {
                return [
                    'status' => 500,
                    'message' => 'User created, but failed to send verification email.',
                ];
            }
        } else {
            return [
                'status' => 400,
                'message' => 'Failed to create user.',
                'errors' => $user->errors,
            ];
        }
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