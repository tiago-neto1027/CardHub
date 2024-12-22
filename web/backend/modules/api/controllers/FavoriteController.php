<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class FavoriteController extends ActiveController
{
    public $modelClass = 'common\models\Favorite';
}