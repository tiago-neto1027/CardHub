<?php

namespace frontend\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use frontend\models\Cart;
use common\models\Card;
use common\models\Listing;
use common\models\Product;

class CartController extends Controller
{
    public function actionViewCart()
    {
        $cartItems = Cart::getItems();
        return $this->render('viewCart',
            ['cartItems' => $cartItems]);
    }
    public function actionAddToCart($id)
    {
        $quantity = Yii::$app->request->post('quantity', 1);
        $Item = Listing::findOne($id);

        if (!$Item) {
            throw new NotFoundHttpException('Product not found');
        }

        Cart::addItem($id, $quantity);

        Yii::$app->session->setFlash('success', 'Item added to cart');
        return $this->redirect(['cart/index']);
    }

    public function actionClearCart()
    {
        Cart::clearCart();

        Yii::$app->session->setFlash('success', 'Cart cleared');
        return $this->redirect(['cart/index']);
    }

    public function actionRemoveFromCart($id)
    {
        $quantity = Yii::$app->request->post('quantity', 1);
        $Item = Product::findOne($id);

        if (!$Item) {
            throw new NotFoundHttpException('Product not found');
        }

        Cart::removeItem($id, $quantity);

        Yii::$app->session->setFlash('success', 'Item removed from cart');
        return $this->redirect(['cart/index']);
    }




}
