<?php

namespace backend\modules\api\controllers;

use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class ProductController extends BaseActiveController
{
    public $modelClass = 'common\models\Product';

    //Filter only active products
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    public function prepareDataProvider()
    {
        $query = Product::find()->where(['status' => 'active']);
        return new ActiveDataProvider(['query' => $query]);
    }
}