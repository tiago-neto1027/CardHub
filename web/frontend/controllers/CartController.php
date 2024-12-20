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
            Cart::addItemToCart($itemId, $item->card->name, $item->image_url ,$item->price, $quantity, $type , 1);
            Yii::$app->session->setFlash('success', ucfirst($type) . ' added to cart.');
        } elseif ($type === 'product') {
            if ($item->stock === 0)
                Yii::$app->session->setFlash('error', 'Not enough products in stock!');
            else {
                Cart::addItemToCart($itemId, $item->name, $item->image_url, $item->price, $quantity, $type, $item->stock);
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

    public function actionUpdateQuantity($itemId, $action = null, $quantity = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $cartKey = Cart::getCartKey();
        $cart = Cart::getItems($cartKey) ?: [];

        if (!isset($cart[$itemId])) {
            return ['success' => false, 'error' => 'Item not found in cart.'];

        }

        $product = Product::findOne($itemId);
        $stock = $product->stock;

        if (!$product) {
            return ['success' => false, 'error' => 'Product not found.'];
        }

        if ($quantity !== null) {
            if ($quantity < 1 || $quantity > $stock) {
                return ['success' => false, 'error' => 'No more stock available'];
            }
            $cart[$itemId]['quantity'] = $quantity;
        } else {
            $currentQuantity = $cart[$itemId]['quantity'];
            if ($action === 'increment') {
                if ($currentQuantity < $stock) {
                    $cart[$itemId]['quantity']++;
                } else {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false, 'error' => ['No more stock available']];
                }
            } elseif ($action === 'decrement') {
                if ($currentQuantity > 1) {
                    $cart[$itemId]['quantity']--;
                } else {
                    return ['success' => false, 'error' => ['Minimum is 1']];
                }
            }
        }

        Cart::setItem($cartKey, $cart);
        $totalCost = Cart::getTotalCost();

        return [
            'success' => true,
            'newQuantity' => $cart[$itemId]['quantity'],
            'newTotal' => Yii::$app->formatter->asCurrency($cart[$itemId]['price']*$cart[$itemId]['quantity']),
            'newCartTotal' => Yii::$app->formatter->asCurrency($totalCost),
        ];

    }
}