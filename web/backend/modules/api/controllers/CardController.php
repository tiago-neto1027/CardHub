<?php

namespace backend\modules\api\controllers;

use common\models\Card;
use common\models\Listing;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Response;

class CardController extends BaseActiveController
{
    public $modelClass = 'common\models\Card';

    //Filter only active cards
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    public function prepareDataProvider()
    {
        $query = Card::find()->where(['status' => 'active']);
        return new ActiveDataProvider(['query' => $query]);
    }

    public function actionCountlistings($id){
        $count = Listing::find()->where(['card_id' => $id])->count();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['listingCount' => $count];
    }
}
