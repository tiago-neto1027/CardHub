<?php

namespace app\controllers;
namespace frontend\controllers;

use \common\models\Product;
use \common\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class CatalogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $data = Product::find()->all();
        
        return $this->render('index', ['products' =>$data]);
    }

    /**
     * Displays a single Product model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
