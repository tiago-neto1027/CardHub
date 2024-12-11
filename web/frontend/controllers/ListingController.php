<?php

namespace frontend\controllers;

use common\models\Listing;
use common\models\ListingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\jui\AutoComplete;

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
                'only' => ['create', 'index', 'delete', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index','delete','view'],
                        'roles' => ['seller'],
                    ],
                ],
            ],
            // Verb filter
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],  // Only allow POST for the delete action
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
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $model->status = 'inactive';

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
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
}
