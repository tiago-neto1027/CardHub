<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class ListingController extends ActiveController
{
    public $modelClass = 'common\models\Listing';
}