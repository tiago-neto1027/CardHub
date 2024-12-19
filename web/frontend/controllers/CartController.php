<?php

namespace frontend\controllers;

use Yii;
use yii\base\Model;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use frontend\models\Cart;
use common\models\Card;
use common\models\Listing;
use common\models\Product;

class CartController extends Controller
{

    public function actionIndex()
    {
        $cartKey = Yii::$app->user->isGuest
            ? 'cart_guest_' . Yii::$app->session->id // For guest users
            : 'cart_' . Yii::$app->user->id;

        $cartItems = Cart::getItems($cartKey);

        // Fetch products for the cart items
        $productIds = array_column($cartItems, 'product_id');
        $products = Product::find()->where(['id' => $productIds])->indexBy('id')->all();

        $totalCost = Cart::getTotalCost();

        return $this->render('index', [
            'cartItems' => $cartItems,
            'products' => $products,
            'totalCost' => $totalCost,
        ]);
    }

    public function actionAddToCart($itemId, $type): \yii\web\Response
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', 'Login to add items to cart.');

        }
        $quantity = 1;
        if ($type === 'card') {
            $item = Listing::findOne($itemId);
        } elseif ($type === 'product') {
            $item = Product::findOne($itemId);
        } else {
            throw new \yii\web\BadRequestHttpException('Invalid item type.');
        }

        if (!$item) {
            throw new \yii\web\NotFoundHttpException(ucfirst($type) . ' not found.');
        }

        if ($type === 'card') {
            Cart::addItemToCart($itemId, $item->card->name, $item->price, $quantity,$type);
            Yii::$app->session->setFlash('success', ucfirst($type) . ' added to cart.');
        } elseif ($type === 'product') {
            if($item->stock = 0)
                Yii::$app->session->setFlash('error', 'Not enough products in stock!');
            else{
                Cart::addItemToCart($itemId, $item->name, $item->price, $quantity, $type);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionRemoveFromCart($itemId)
    {
        Cart::removeItem($itemId);
        Yii::$app->session->setFlash('success', 'Item removed from cart.');
        return $this->redirect(['cart/index']);
    }

    public function actionClearCart()
    {
        Cart::clearCart();
        Yii::$app->session->setFlash('success', 'Cart cleared');
        return $this->redirect(['cart/index']);
    }

}
