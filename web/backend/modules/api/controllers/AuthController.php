<?php

namespace backend\modules\api\controllers;

use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'except' => ['login'],
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
            'status' => 'success',
            'message' => 'Login successful',
        ];
    }
}