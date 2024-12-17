<?php

namespace app\controllers;
namespace frontend\controllers;

use \common\models\Product;
use \common\models\Card;
use common\models\ListingSearch;
use \common\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class CatalogController extends \yii\web\Controller
{
    public function actionIndex($id, $type)
    {   
        $request = \Yii::$app->request;          
        $productTypeFilter = $request->get('productType', null); 
        $page = $request->get('page', 1);               
        $pageSize = 6;                                 // Number of items per page

        $productQuery = Product::find();
        $cardQuery = Card::find();
        

        // Applying Game filter
        if ($id !== null) {
            $productQuery->andWhere(['game_id' => $id]); 
            $cardQuery->andWhere(['game_id' => $id]);   
        }

        if ($productTypeFilter !== null) {
            $productQuery->andWhere(['type' => $productTypeFilter]);
        }


        // Fetching data based on type
        if ($type === 'product') {
            $searchModel = new ProductSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
            $query = $productQuery;
        } elseif ($type === 'card') {
            $searchModel = new ListingSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
            
            $query = $cardQuery;
        } else {
            $allProducts = $productQuery->all();
            $allCards = $cardQuery->all();
            $allItems = array_merge($allProducts, $allCards);  

            // Sorting
            usort($allItems, function ($a, $b) {
                return strtotime($b->created_at) - strtotime($a->created_at); 
            });

            // Pagination
            $totalCount = count($allItems);
            $items = array_slice($allItems, ($page - 1) * $pageSize, $pageSize);

            return $this->render('index', [
                'products' => $items,  
                'totalCount' => $totalCount,
                'page' => $page,
                'type' => $type,
                'pageSize' => $pageSize,
                'productType' => $productTypeFilter, 
            ]);
        }

        //pagination for single type
        $totalCount = $query->count();
        $items = $query->offset(($page - 1) * $pageSize)
                    ->limit($pageSize)
                    ->all();

        return $this->render('index', [
            'products' => $items,                                               //(Product or Card)
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
            'productType' => ($type === 'product') ? $productTypeFilter : null, // Only include productType for products
        ]);
    }
    /**
     * Displays a single Product model.
     */
    public function actionView($id, $type)
    {
            // Determines the model by type
        if ($type === 'product') {
            $model = Product::findOne($id);
            
        } elseif ($type === 'card') {
            $model = Card::findOne($id);
        } else {
            throw new \yii\web\BadRequestHttpException('Invalid type provided.');
        }

        // If the model doesn't exist, throw a 404 exception
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('The requested item does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
            'type' => $type, // Optionally pass the type to the view
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
