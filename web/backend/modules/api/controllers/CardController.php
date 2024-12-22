<?php

namespace backend\modules\api\controllers;

use common\models\Listing;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class CardController extends ActiveController
{
    public $modelClass = 'common\models\Card';

    public function actionCountlistings($id){
        $count = Listing::find()->where(['card_id' => $id])->count();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['listingCount' => $count];
    }
}
