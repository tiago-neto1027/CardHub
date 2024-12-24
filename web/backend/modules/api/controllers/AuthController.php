@ -0,0 +1,47 @@
<?php

use common\models\User;
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
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

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