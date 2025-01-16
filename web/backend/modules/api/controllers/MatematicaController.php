<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class MatematicaController extends BaseActiveController
{
    public $modelClass = 'common\models\Game';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function actionRaizdois()
    {
        return [sqrt(2)];
    }
}