<?php

namespace backend\controllers;

use common\models\Card;
use common\models\Listing;
use common\models\LoginForm;
use common\models\Product;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'roles' => ['?'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ["admin",'manager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $registeredUsers = User::getRegisteredUsersCount();
        $soldProducts = Product::getSoldProductsCount();
        $soldListings = Listing::getSoldListingsCount();
        $revenueGenerated = Product::getTotalRevenue();
        $pendingCards = Card::getPendingCardCount();
        $lowStockProducts = Product::getLowStockCount();

        return $this->render('index', [
            'registeredUsers' => $registeredUsers,
            'soldProducts' => $soldProducts,
            'soldListings' => $soldListings,
            'revenueGenerated' => $revenueGenerated,
            'pendingCards' => $pendingCards,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->can('admin') || Yii::$app->user->can('manager')) {
                return $this->goHome();
            }
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            if(Yii::$app->user->can('admin') || Yii::$app->user->can('manager')){
                return $this->goHome();
            }
            else
            {
                Yii::$app->user->logout();
                $model->addError('username', 'You do not have access to the backend.');
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
