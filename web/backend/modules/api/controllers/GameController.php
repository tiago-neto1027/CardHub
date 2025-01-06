<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class GameController extends BaseActiveController
{
    public $modelClass = 'common\models\Game';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

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
