<?php

namespace backend\modules\api\controllers;

use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

class UserController extends BaseActiveController{

    public $modelClass = 'backend\modules\api\models\User';
    public $user = null;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'authintercept'],
        ];
        return $behaviors;
    }

    public function authintercept($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            $this->user=$user;
            return $user;
        }
        throw new ForbiddenHttpException('Wrong credentials.');
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($this->user) {
            if ($model && $model->user_id != $this->user->id) {
                throw new ForbiddenHttpException('You are not allowed to access or modify another user\'s details.');
            }
        }
    }

    public function actionGetEmail(){
        return ['email' => $this->user->email];
    }

    public function actionChangeUsername() {
        $data = Yii::$app->request->post();
        $newUsername = $data['username'];
        if (empty($data['username'])) {
            return [
                'status' => 'fail',
                'message' => 'Username required.'
            ];
        }

        if (User::findByUsernameAll($data['username']) !== null) {
            return [
                'status' => 'fail',
                'message' => 'Username is already taken.'
            ];
        }

        if ($newUsername) {
            $this->user->username = $newUsername;
            if ($this->user->save()) {
                return ['username' => $this->user->username];
            } else {
                throw new BadRequestHttpException('Failed to change username.');
            }
        }
        throw new BadRequestHttpException('Invalid username or username already taken.');
    }

    public function actionChangeEmail() {
        $data = Yii::$app->request->post();
        $newEmail = $data['email'];
        if (empty($data['email'])) {
            return [
                'status' => 'fail',
                'message' => 'Email required.'
            ];
        }
        if (User::findByEmailAll($data['email']) !== null) {
            return [
                'status' => 'fail',
                'message' => 'Email is already taken.'
            ];
        }

        if ($newEmail) {
            $this->user->email = $newEmail;
            if ($this->user->save()) {
                return ['email' => $this->user->email];
            } else {
                throw new BadRequestHttpException('Failed to change email.');
            }
        }
        throw new BadRequestHttpException('Invalid email or email already taken.');
    }
}