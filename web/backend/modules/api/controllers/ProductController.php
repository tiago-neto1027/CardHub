<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class ProductController extends ActiveController
{
    public $modelClass = 'common\models\Product';
}