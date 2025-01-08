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
        $role = $auth->getRole('buyer');
        if (!$role) {
            $role = $auth->createRole('buyer');
            $auth->add($role);
        }
    }

    public function load()
    {
        parent::load();

        foreach ($this->data as $data) {
            $user = User::findOne($data['id']);
            if ($user) {
                $user->setRole($user->id, 'buyer');
            }
        }
    }
}

