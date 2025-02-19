<?php

namespace app\controllers;
namespace frontend\controllers;

use common\models\Game;
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

    public function actionIndex($game, $type)
    {
        $productQuery = Product::find();
        $cardQuery = Listing::find();
        $game_id = Game::findOne((['name'=>$game]));
        if ($game !== null) {
            $productQuery->andWhere(['game_id' => $game_id])
                ->andWhere(['>  ', 'stock', 0]);
            $cardQuery->joinWith('card')
                ->andWhere(['cards.game_id' => $game_id])
                ->andWhere(['listings.status' => 'active']);
        }

        if ($type === 'product') {
            $searchModel = new ProductSearch();
            $query = $productQuery;
            $productTypes = \common\models\Product::getProductTypes();
        }
        elseif($type === 'listing'){
            $searchModel = new ListingSearch();
            $query = $cardQuery;
            $productTypes = null;

        }
        else{
            return $this->redirect(['site/error']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $games = Game::getAllGames();

        return $this->render('index', [
            'games' => $games,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
            'productTypes' => $productTypes,
            'isActiveView' => true,
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
