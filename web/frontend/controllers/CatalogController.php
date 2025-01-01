<?php

namespace app\controllers;
namespace frontend\controllers;

use \common\models\Product;
use Yii;
use \common\models\Card;
use common\models\ListingSearch;
use \common\models\ProductSearch;
use \common\models\Listing;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class CatalogController extends \yii\web\Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['?', 'seller','buyer'],
                        ]
                    ],
                ],
            ]
        );
    }

    public function actionIndex($id, $type)
    {   
        if ($type === 'product') {
            $searchModel = new ProductSearch();
            $queryParams = Yii::$app->request->queryParams;
    
                // add game id to the parameters
                $queryParams['ProductSearch']['game_id'] = $id;
    
            $dataProvider = $searchModel->search($queryParams);
        } elseif ($type === 'listing') {
            $searchModel = new ListingSearch();
            $queryParams = Yii::$app->request->queryParams;
    
                // add game id to the parameters
                $queryParams['ListingSearch']['game_id'] = $id;
    
            $dataProvider = $searchModel->search($queryParams);
        } else {
            return $this->redirect(['site/error']);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single Product model.
     */
    /*
    public function actionView($id, $type)
    {
            // Determines the model by type
        if ($type === 'product') {
            $model = Product::findOne($id);
            
        } elseif ($type === 'listing') {
            $model = Listing::findOne($id);
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
    */

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
