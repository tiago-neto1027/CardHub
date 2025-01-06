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
}