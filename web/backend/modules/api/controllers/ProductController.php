<?php

namespace backend\modules\api\controllers;

use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class ProductController extends BaseActiveController
{
    public $modelClass = 'common\models\Product';

    //Filter only active products
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }
    public function prepareDataProvider()
    {
        $query = Product::find()->where(['status' => 'active']);
        return new ActiveDataProvider(['query' => $query]);
    }

    public function actionCreate()
    {
        throw new ForbiddenHttpException('Create action is disabled.');
    }

    public function actionUpdate($id)
    {
        throw new ForbiddenHttpException('Update action is disabled.');
    }

    public function actionDelete($id)
    {
        throw new ForbiddenHttpException('Delete action is disabled.');
    }
}