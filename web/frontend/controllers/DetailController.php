<?php

namespace frontend\controllers;

use yii\web\Controller;

class DetailController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDetails($id)
    {
        // Logic to find the model and render the view
        return $this->render('details', ['id' => $id]);
    }

}
