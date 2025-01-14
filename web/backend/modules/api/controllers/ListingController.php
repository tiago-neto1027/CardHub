<?php

namespace backend\modules\api\controllers;

use Bluerhinos\phpMQTT;
use common\models\Card;
use common\models\Favorite;
use common\models\Listing;
use common\models\User;
use Exception;
use Yii;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class ListingController extends BaseActiveController
{
    public $modelClass = 'common\models\Listing';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function actionIndex()
    {
        //Filters for listings that are active only
        $listings = Listing::find()
            ->where(['status' => 'active'])
            ->all();

        $result = [];
        foreach ($listings as $listing) {
            $seller = User::findOne($listing->seller_id);
            $card = Card::findOne($listing->card_id);

            //Adds the seller_username to the listing jsonObject
            $result[] = [
                'id' => $listing->id,
                'seller_id' => $listing->seller_id,
                'seller_username' => $seller ? $seller->username : null,
                'card_id' => $listing->card_id,
                'card_name' => $card ? $card->name : null,
                'card_image_url' => $card ? $card->image_url : null,
                'price' => $listing->price,
                'condition' => $listing->condition,
                'status' => $listing->status,
                'created_at' => $listing->created_at,
                'updated_at' => $listing->updated_at,
            ];
        }

        return $result;
    }


    //For now every action is unset because there are no sellers in the mobile app
    //Add the listing actions here if later on it's implemented on the mobile app
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