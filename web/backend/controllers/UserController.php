<?php

namespace backend\controllers;

use backend\models\SignupForm;
use common\models\User;
use common\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => \yii\filters\VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['update'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['update'],
                            'roles' => ['admin'],
                            'matchCallback' => function ($rule, $action) {
                                $auth = Yii::$app->authManager;
                                $targetId = Yii::$app->request->get('id');
                                $userId = Yii::$app->user->id;
                                $userRole = $auth->getRolesByUser($userId); // Current user's roles
                                $targetRole = $auth->getRolesByUser($targetId); // Target user's roles

                                return isset($userRole['admin']) &&
                                    ($userId == $targetId || !isset($targetRole['admin']));
                            },
                        ],
                    ],

                ],
            ]
        );
    }


    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleted()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, true);

        return $this->render('deleted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSellers()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, false, true);

        return $this->render('sellers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SignupForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->signup()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;

        $model = $this->findModel($id);
        $userRoles = $auth->getRolesByUser($id);


        if ($this->request->isPost) {
            if((isset($userRoles['admin'])) && (Yii::$app->user->id != $id)){
                throw new ForbiddenHttpException('You are not allowed to update this user.');
            }
            if ($model->load($this->request->post()) && $model->save()) {
                $roleName = $this->request->post('User')['role'];

                $role = $auth->getRole($roleName);
                if (!$role) {
                    throw new BadRequestHttpException('Invalid role specified.');
                }

                $model->setRole($id, $roleName);
                $model->setRole($id, $roleName);

                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $user->status = 0;

        if ($user->save(false)) {
            Yii::$app->session->setFlash('success', 'User status updated to Deleted.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update user status.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
