<?php

namespace backend\controllers;

use common\models\Card;
use common\models\InvoiceLine;
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
        //Date Calculations
        $currentDate = new \DateTime();
        $previousMonth = (clone $currentDate)->modify('-1 month')->format('m');
        $previousMonthName = (clone $currentDate)->modify('-1 month')->format('F');
        $twoMonthsAgo = (clone $currentDate)->modify('-2 months')->format('m');
        $twoMonthsAgoName = (clone $currentDate)->modify('-2 months')->format('F');
        $oneMonthAgoYear = (clone $currentDate)->modify('-1 month')->format('Y');
        $twoMonthsAgoYear = (clone $currentDate)->modify('-2 months')->format('Y');

        //Profit Calculations
        $cardProfitPreviousMonth = InvoiceLine::calculateMonthlyProfit($previousMonth, $oneMonthAgoYear, 'card');
        $cardProfitTwoMonthsAgo = InvoiceLine::calculateMonthlyProfit($twoMonthsAgo, $twoMonthsAgoYear, 'card');

        $productProfitPreviousMonth = InvoiceLine::calculateMonthlyProfit($previousMonth, $oneMonthAgoYear, 'product');
        $productProfitTwoMonthsAgo = InvoiceLine::calculateMonthlyProfit($twoMonthsAgo, $twoMonthsAgoYear, 'product');

        $registeredUsers = User::getRegisteredUsersCount();
        $soldProducts = Product::getSoldProductsCount();
        $soldListings = Listing::getSoldListingsCount();
        $revenueGenerated = Product::getTotalRevenue();
        $pendingCards = Card::getPendingCardCount();
        $lowStockProducts = Product::getLowStockCount();
        $noStockProducts = Product::getNoStockCount();

        return $this->render('index', [
            'previousMonthName' => $previousMonthName,
            'twoMonthsAgoName' => $twoMonthsAgoName,
            'cardProfitLastMonth' => $cardProfitPreviousMonth,
            'productProfitLastMonth' => $productProfitPreviousMonth,
            'cardProfitTwoMonthsAgo' => $cardProfitTwoMonthsAgo,
            'productProfitTwoMonthsAgo' => $productProfitTwoMonthsAgo,

            'registeredUsers' => $registeredUsers,
            'soldProducts' => $soldProducts,
            'soldListings' => $soldListings,
            'revenueGenerated' => $revenueGenerated,
            'pendingCards' => $pendingCards,
            'lowStockProducts' => $lowStockProducts,
            'noStockProducts' => $noStockProducts,
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
