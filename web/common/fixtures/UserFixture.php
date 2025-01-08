<?php

namespace common\fixtures;

use yii\test\ActiveFixture;
use common\models\User;
use Yii;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'common\models\User';
    public $dataFile = '@frontend/tests/_data/users.php';

    public function init()
    {
        parent::init();

        $auth = Yii::$app->authManager;

        $buyerRole = $auth->getRole('buyer');
        if (!$buyerRole) {
            $buyerRole = $auth->createRole('buyer');
            $auth->add($buyerRole);
        }

        $sellerRole = $auth->getRole('seller');
        if (!$sellerRole) {
            $sellerRole = $auth->createRole('seller');
            $auth->add($sellerRole);
        }
    }

    public function load()
    {
        parent::load();

        foreach ($this->data as $data) {
            $user = User::findOne($data['id']);

            if ($user) {
                $auth = Yii::$app->authManager;

                if ($user->username === 'user_seller') {
                    if (!$auth->getAssignment('seller', $user->id)) {
                        $user->setRole($user->id, 'seller');
                    }
                } elseif($user->username === 'T3st Us3r') {
                    if (!$auth->getAssignment('admin', $user->id)) {
                        $user->setRole($user->id, 'admin');
                    }
                } else {
                    if (!$auth->getAssignment('buyer', $user->id)) {
                        $user->setRole($user->id, 'buyer');
                    }
                }
            }
        }
    }

    public function _before()
    {
        Yii::$app->db->createCommand('TRUNCATE TABLE auth_assignment')->execute();
    }
}

