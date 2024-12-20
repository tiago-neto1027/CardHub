<?php

namespace frontend\controllers;

use common\models\Product;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\jui\AutoComplete;

/**
 * ListingController implements the CRUD actions for Listing model.
 */
class ProductController extends Controller
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
                    'only' => ['index', 'view'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index','view'],
                            'roles' => ['@'],
                        ],
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
    /*public function actionIndex()
    {

    }*/

    public function actionView($id)
    {
        $model = Product::findOne($id);
        $type = $model instanceof Product ? 'product' : 'card';

        if ($type !== 'product') {
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


    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
