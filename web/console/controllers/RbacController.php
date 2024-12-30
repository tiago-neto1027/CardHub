<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $manager = $auth->createRole('manager');
        $buyer = $auth->createRole('buyer');
        $seller = $auth->createRole('seller');

        $auth->add($admin);
        $auth->add($manager);
        $auth->add($buyer);
        $auth->add($seller);

        $permissions = [
            'createUser' => 'Create a user',
            'updateUser' => 'Update user details',
            'deleteUser' => 'Delete a user',
            'createCard' => 'Create a card',
            'updateCard' => 'Update a card',
            'deleteCard' => 'Delete a card',
        ];

        foreach ($permissions as $permissionName => $description) {
            $$permissionName = $auth->createPermission($permissionName);
            $$permissionName->description = $description;
            $auth->add($$permissionName);
        }

        $auth->addChild($buyer, $updateUser);
        $auth->addChild($buyer, $deleteUser);

        $auth->addChild($seller, $buyer);
        $auth->addChild($manager, $seller);
        $auth->addChild($admin, $manager);
    }

}