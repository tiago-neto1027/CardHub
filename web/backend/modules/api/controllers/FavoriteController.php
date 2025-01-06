<?php

namespace backend\modules\api\controllers;

use common\models\Card;
use common\models\Favorite;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

class FavoriteController extends BaseActiveController
{
    public $modelClass = 'common\models\Favorite';
    public $user=null;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'authintercept'],
        ];
        return $behaviors;
    }
    public function authintercept($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            $this->user=$user;
            return $user;
        }
        throw new ForbiddenHttpException('Wrong credentials.');
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($this->user) {
            if ($model && $model->user_id != $this->user->id) {
                throw new ForbiddenHttpException('You are not allowed to access or modify another user\'s favorite.');
            }
        }
    }

    public function actionIndex()
    {
        if (!$this->user) {
            throw new UnauthorizedHttpException('You must be logged in to view favorites.');
        }

        return Favorite::find()->where(['user_id' => $this->user->id])->all();
    }

    public function actionCreate()
    {
        if (!$this->user) {
            throw new UnauthorizedHttpException('You must be logged in to add a favorite.');
        }

        //Verifies whether listing_id was received
        $cardId = Yii::$app->request->post('card_id');
        if (!$cardId) {
            throw new ForbiddenHttpException('Card ID is required.');
        }

        //Verifies if the listing was found
        $card = Card::findOne($cardId);
        if (!$card) {
            throw new ForbiddenHttpException('Card not found.');
        }

        //Creates a new favorite
        $favorite = new Favorite();
        $favorite->user_id = $this->user->id;
        $favorite->card_id = $cardId;

        //Saves the favorite
        if ($favorite->save()) {
            return $favorite;
        } else {
            return $favorite->errors;
        }
    }

    public function actionDelete($id)
    {
        if (!$this->user) {
            throw new UnauthorizedHttpException('You must be logged in to delete a favorite.');
        }

        //Finds the favorite by ID
        $favorite = Favorite::findOne($id);
        if (!$favorite) {
            throw new ForbiddenHttpException('Favorite not found.');
        }

        //Verifies if the favorite belongs to the logged in user
        if ($favorite->user_id != $this->user->id) {
            throw new ForbiddenHttpException('You are not allowed to delete another user\'s favorite.');
        }

        //Deletes the favorite
        if ($favorite->delete()) {
            return ['message' => 'Favorite deleted successfully.'];
        } else {
            return $favorite->errors;
        }
    }

    public function actionUpdate($id)
    {
        throw new ForbiddenHttpException('Update action is disabled.');
    }
}