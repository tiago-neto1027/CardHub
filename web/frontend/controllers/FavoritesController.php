<?php

namespace frontend\controllers;

use common\models\CardSearch;
use common\models\Favorites;
use common\models\FavoritesSearch;
use common\models\ListingSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;
use common\models\Listing;


class FavoritesController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index', 'create', 'view', 'update', 'delete'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'create'],
                            'roles' => ['seller', 'buyer'], // No matchCallback needed
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view', 'update', 'delete'],
                            'roles' => ['seller', 'buyer'],
                            'matchCallback' => function ($rule, $action) {
                                $modelId = Yii::$app->request->get('id'); // Assume `id` is passed as a parameter
                                $model = Favorites::findOne($modelId);
                                return $model && $model->user_id == Yii::$app->user->id;
                            },
                        ],
                    ],
                ],
            ]
        );
    }


    public function actionIndex()
    {
        $searchModel = new FavoritesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); // Use queryParams to pass the filters

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'You must be logged in to add to favorites.');
            return $this->redirect(['/site/login']);
        }

        $cardId = $id;

        $userId = Yii::$app->user->id;

        $existingFavorite = Favorites::findOne(['card_id' => $cardId, 'user_id' => $userId]);
        if ($existingFavorite) {
            Yii::$app->session->setFlash('error', 'This card is already in your favorites.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $favorite = new Favorites();
        $favorite->card_id = $cardId;
        $favorite->user_id = $userId;

        if ($favorite->save()) {
            Yii::$app->session->setFlash('success', 'Card added to your favorites.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to add card to favorites.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemove($id)
    {
        $userId = Yii::$app->user->id;

        $favorite = Favorites::findOne(['card_id' => $id, 'user_id' => $userId]);

        if ($favorite) {
            $favorite->delete();
            Yii::$app->session->setFlash('success', 'Card removed from favorites.');
        } else {
            Yii::$app->session->setFlash('error', 'Card not found in your favorites.');
        }
        return $this->redirect(['index']);
    }

}