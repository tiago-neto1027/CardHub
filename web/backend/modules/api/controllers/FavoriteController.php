<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class FavoriteController extends BaseActiveController
{
    public $modelClass = 'common\models\Favorite';
}