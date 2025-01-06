<?php

namespace backend\modules\api\controllers;

use common\models\Card;
use common\models\Listing;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class CardController extends BaseActiveController
{
    public $modelClass = 'common\models\Card';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Enable authentication for all actions by default
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];

        return $behaviors;
    }

    // This method will be used to allow access without authentication for 'countlistings' action
    public function beforeAction($action)
    {
        // Skip authentication for the 'countlistings' action
        if ($action->id === 'countlistings') {
            return true;  // Allow access without authentication
        }

        // Otherwise, proceed with authentication for other actions
        return parent::beforeAction($action);  // Default authentication behavior
    }


    //Filter only active cards
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    public function prepareDataProvider()
    {
        $query = Card::find()->where(['status' => 'active']);
        return new ActiveDataProvider(['query' => $query]);
    }

    public function actionCountListings($id){
        $count = Listing::find()->where(['card_id' => $id])->count();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['listingCount' => $count];
    }

}