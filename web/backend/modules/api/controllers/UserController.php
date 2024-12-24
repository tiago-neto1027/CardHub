<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class UserController extends BaseActiveController
{
    public $modelClass = 'common\models\User';
}
