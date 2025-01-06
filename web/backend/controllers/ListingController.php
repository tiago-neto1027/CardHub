<?php

namespace backend\controllers;

use Bluerhinos\phpMQTT;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Listing;
use common\models\Favorite;
use common\models\User;


class ListingController extends Controller
{
    public function actionPublishNewListing()
    {
        $listing_id = Yii::$app->request->post('listing_id');
        $card_id = Yii::$app->request->post('card_id');
        $name = Yii::$app->request->post('name');
        $price = Yii::$app->request->post('price');

        $listing = Listing::findOne($listing_id);

        $favorites = Favorite::find()->where(['card_id' => $card_id])->all();

        foreach ($favorites as $favorite) {
            $user = User::findOne($favorite->user_id);

            $this->publishNewListing($listing, $user->id);
        }

        return $this->asJson(['status' => 'success']);
    }

        private function publishNewListing($listing, $user_id)
        {
            $server = "localhost";
            $port = 1883;
            $username = "";
            $password = "";
            $client_id = "backend-client-" . uniqid();

            $mqtt = new phpMQTT($server, $port, $client_id);

            if ($mqtt->connect(true, NULL, $username, $password)) {
                $listing_data = [
                    'listing_id' => $listing->id,
                    'card_id' => $listing->card_id,
                    'name' => $listing->name,
                    'price' => $listing->price,
                ];

                $message = json_encode($listing_data);

                $mqtt->publish("new_listing/{$user_id}", $message, 0);

                $mqtt->close();
            }
        }
}