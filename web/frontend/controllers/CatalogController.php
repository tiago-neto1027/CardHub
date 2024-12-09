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
        $data = null;
        $request = \Yii::$app->request;

        $page = $request->get('page', 1);   // Default to page 1
        $pageSize = 12;                     // Number of products per page

        $filter = $request->get('filter');
        if ($filter != null) {
            $words = explode(' ', $filter);

            // ORIGINAL FILTER $data = Product::find()->where(['like', 'name', $filter])->all();
            // < MULTI-WORD FILTER:
            $query = Product::find();
            foreach ($words as $word) {
                $query->andWhere(['like', 'name', $word],);
            }

            $data = $query->all();
            
        } else {
            $query = Product::find();
            $data = Product::find()->all();
        } 

        $totalCount = $query->count();      // Total number of products matching the filter
        $data = $query  ->offset(($page - 1) * $pageSize)
                        ->limit($pageSize)
                        ->all();
        
        return $this->render('index', ['products' =>$data,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize
        ]);
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
