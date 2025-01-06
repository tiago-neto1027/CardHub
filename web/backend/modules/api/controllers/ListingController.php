<?php

namespace backend\modules\api\controllers;

use Bluerhinos\phpMQTT;
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
        unset($actions['create'], $actions['update'], $actions['delete']);

        return $actions;
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

        $successfullUsers = [];
        $failedUsers = [];

        foreach ($favorites as $favorite) {
            $user = User::findOne($favorite->user_id);

            if (!$user) {
                $failedUsers[] = $favorite->user_id;
                continue;
            }

            try {
                $this->publishNewListing($listing, $user->id);
                    \Yii::info("Successfully notified user: {$user->username}", 'application');
                    $successfullUsers[] = $user->username;
            } catch (\Exception $e) {
                $failedUsers[] = $user->username;
                \Yii::error("Failed to notify user {$user->id}: {$e->getMessage()}", 'application');
            }
        }

        return $this->asJson([
            'status' => 'success',
            'message' => 'Notifications sent.',
            'successfullUsers' => $successfullUsers,
            'failedUsers' => $failedUsers,
        ]);
    }

    private function publishNewListing($listing, $user_id)
    {

        $server = "cardhub";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "backend-client-" . uniqid();

        $mqtt = new phpMQTT($server, $port, $client_id);

        if ($mqtt->connect(true, NULL, $username, $password)) {
            \Yii::info("Successfully connected to MQTT broker.", 'application');
            try {
                \Yii::error("Attempting to publish message to topic new listing.", 'application');

                $listing_data = [
                    'listing_id' => $listing->id,
                    'card_id' => $listing->card_id,
                    'name' => $listing->card->name,
                    'price' => $listing->price,
                ];

                $message = json_encode($listing_data);
                $topic = "new_listing";

                $mqtt->publish($topic, $message, 0);
                \Yii::info("Message published to topic {$topic}.", 'application');
            } catch (\Exception $e) {
                \Yii::error("Exception caught: " . $e->getMessage(), 'application');
            }

            $mqtt->close();
        } else {
            \Yii::error("Unable to connect to MQTT broker.", 'application');
            throw new \Exception("Unable to connect to MQTT broker.");
        }
    }
}