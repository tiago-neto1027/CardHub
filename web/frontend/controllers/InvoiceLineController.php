<?php

namespace app\controllers;

class InvoiceLineController extends \yii\web\Controller
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
                    'only' => ['index','view'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index','view'],
                            'roles' => ['seller','buyer'],
                        ]
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
