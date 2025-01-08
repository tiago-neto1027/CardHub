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
                if ($user->username === 'user_seller') {
                    $user->setRole($user->id, 'seller');
                } else {
                    $user->setRole($user->id, 'buyer');
                }
            }
        }
    }
}

