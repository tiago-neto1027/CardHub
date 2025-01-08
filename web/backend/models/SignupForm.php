<?php

namespace backend\models;

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
    public $role;
    public $status;


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
            ['password', 'string', 'min' => 8],

            ['role', 'required'],
            ['role', 'in', 'range' => ['manager', 'admin', 'seller', 'buyer']],

            ['status', 'in', 'range' => [0, 9, 10]],
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
            $user->status = 10;

            $rolename = $this->role ?: 'buyer';

            if ($user->save()) {
                $auth = Yii::$app->authManager;

                $role = $auth->getRole($rolename);
                if ($role) {
                    $auth->assign($role, $user->getId());
                } else {
                    throw new \Exception('Error: Role not found.');
                }
                return $user;
            }
        }
        return null;
    }


    public function update($userId)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try{
            $user = User::findOne($userId);
            if (!$user) {
                throw new \Exception('User not found.');
            }

            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $this->status;
            if (!$user->save()) {
                throw new \Exception('Failed to save user: ' . json_encode($user->errors));
            }

            $auth = Yii::$app->authManager;
            $auth->revokeAll($user->id);
            $role = $auth->getRole($this->role);
            if (!$role) {
                throw new \Exception('Role not found.');
            }
            $auth->assign($role, $user->id);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('Failed to update user: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
