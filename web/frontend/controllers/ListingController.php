<?php

namespace frontend\controllers;

use Bluerhinos\phpMQTT;
use common\models\Favorite;
use common\models\Listing;
use common\models\ListingSearch;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\jui\AutoComplete;
use GuzzleHttp\Client;

/**
 * ListingController implements the CRUD actions for Listing model.
 */
class ListingController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['create', 'delete','update'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['seller'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete','update'],
                            'roles' => ['seller'],
                            'matchCallback' => function ($rule, $action) {
                                $listingId = Yii::$app->request->get('id');
                                $listing = Listing::findOne($listingId);
                                return $listing && $listing->seller_id == Yii::$app->user->id;
                            },
                        ],
                    ],
                ],
                // Verb filter
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],

                    ],
                ],
            ]
       );
    }



    /**
     * Lists all Listing models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search(array_merge(
            $this->request->queryParams,
            [
                'seller_id' => Yii::$app->user->id,
                'status' => 'active'
            ]
        ));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'isActiveView' => true,
        ]);
    }

    public function actionCanceled()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search(array_merge(
            $this->request->queryParams,
            [
                'seller_id' => Yii::$app->user->id,
                'status' => 'inactive'
            ]
        ));

        return $this->render('canceled', [
            'dataProvider' => $dataProvider,
            'isActiveView' => false,
        ]);
    }

    public function actionSold()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search(array_merge(
            $this->request->queryParams,
            [
                'seller_id' => Yii::$app->user->id,
                'status' => 'sold'
            ]
        ));

        return $this->render('sold', [
            'dataProvider' => $dataProvider,
            'isActiveView' => false,
        ]);
    }


    /**
     * Creates a new Listing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Listing();
        $model->seller_id = \Yii::$app->user->identity->id;
        $model->status = 'active';

        if ($model->load($this->request->post()) && $model->save()) {
            $this->newListing($model->id);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCardList($term)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $cards = \common\models\Card::find()
            ->select(['id', 'name AS label'])
            ->where(['like', 'name', $term])
            ->andWhere(['status' => 'active'])
            ->asArray()
            ->all();

        return $cards;
    }

    /**
     * Deletes an existing Listing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Listing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Listing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Listing::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionView($id)
    {
        $model = Listing::findOne($id);
        $type = $model instanceof Listing ? 'listing' : 'product';

        if ($type !== 'listing') {
            throw new \yii\web\BadRequestHttpException('Invalid type provided.');
        }

        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('The requested item does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    private function newListing($id)
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

        /*return $this->asJson([
            'status' => 'success',
            'message' => 'Notifications sent.',
            'successfullUsers' => $successfullUsers,
            'failedUsers' => $failedUsers,
        ]);*/
    }

    private function publishNewListing($listing)
    {
        $server = "13.39.156.210";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "backend-client-" . uniqid();

        $mqtt = new phpMQTT($server, $port, $client_id);

        if ($mqtt->connect(true, NULL, $username, $password)) {
            \Yii::info("Successfully connected to MQTT broker.", 'application');
            try {

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
