<?php

namespace backend\modules\api\controllers;

use Bluerhinos\phpMQTT;
use common\models\Favorite;
use common\models\Listing;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class ListingController extends BaseActiveController
{
    public $modelClass = 'common\models\Listing';

    public function actionPublishnewlisting($id)
    {
        $listing = Listing::findOne($id);
        if (!$listing) {
            return $this->asJson([
                'status' => 'error',
                'message' => 'Listing not found.',
            ]);
        }
        $favorites = Favorite::find()->where(['card_id' => $listing->card_id])->all();
        if (empty($favorites)) {
            return $this->asJson([
                'status' => 'success',
                'message' => 'No users found with this card in their favorites.',
            ]);
        }

        $failedUsers = [];

        foreach ($favorites as $favorite) {
            $user = User::findOne($favorite->user_id);

            if (!$user) {
                $failedUsers[] = $favorite->user_id;
                continue;
            }

            try {
                $this->publishNewListing($listing, $user->id);
            } catch (\Exception $e) {
                $failedUsers[] = $user->id;
                \Yii::error("Failed to notify user {$user->id}: {$e->getMessage()}", 'application');
            }
        }

        return $this->asJson([
            'status' => 'success',
            'message' => 'Notifications sent.',
            'failedUsers' => $failedUsers,
        ]);
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

            $topic = "new_listing/{$user_id}";
            $mqtt->publish($topic, $message, 0);

            $mqtt->close();
        } else {
            throw new \Exception("Unable to connect to MQTT broker.");
        }
    }
}