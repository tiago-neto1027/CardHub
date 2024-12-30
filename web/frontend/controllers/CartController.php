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
                            'actions' => ['index', 'view'],
                            'roles' => ['?', 'seller', 'buyer'],
                        ]
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $cartKey = Yii::$app->user->isGuest
            ? 'cart_guest_' . Yii::$app->session->id
            : 'cart_' . Yii::$app->user->id;

        $cartItems = Cart::getItems($cartKey);

        $productIds = array_column($cartItems, 'itemId');

        $totalCost = Cart::getTotalCost();

        return $this->render('index', [
            'cartItems' => $cartItems,
            'totalCost' => $totalCost,
        ]);
    }

    public function actionAddToCart($itemId, $type): \yii\web\Response
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', 'Login to add items to cart.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($type === 'listing') {
            $item = Listing::findOne($itemId);
        } elseif ($type === 'product') {
            $item = Product::findOne($itemId);
        } else {
            throw new \yii\web\BadRequestHttpException('Invalid item type.');
        }

        if (!$item) {
            throw new \yii\web\NotFoundHttpException(ucfirst($type) . ' not found.');
        }

        $quantity = 1;

        if ($type === 'listing') {
            if ($item->status === 'inactive') {
                if ($item->seller_id === Yii::$app->user->id) {
                    Yii::$app->session->setFlash('error', "You can't buy your own items.");
                } else {
                    Yii::$app->session->setFlash('error', 'Item not for sale');
                }
            } else {
                Cart::addItemToCart($itemId, $item->card->name, $item->card->image_url, $item->price, $quantity, $type, 1);
            }
        }

        if ($type === 'product') {
            if ($item->status === 'inactive') {
                Yii::$app->session->setFlash('error', 'Item not for sale');
            } elseif ($item->stock === 0) {
                Yii::$app->session->setFlash('error', 'Not enough products in stock!');
            } else {
                Cart::addItemToCart($itemId, $item->name, $item->image_url, $item->price, $quantity, $type, $item->stock);
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }



    public
    function actionRemoveFromCart($type, $itemId)
    {
        Cart::removeItem($type, $itemId);
        Yii::$app->session->setFlash('success', 'Item removed from cart.');
        return $this->redirect(['cart/index']);
    }

    public
    function actionClearCart()
    {
        Cart::clearCart();
        Yii::$app->session->setFlash('success', 'Cart cleared');
        return $this->redirect(['cart/index']);
    }

    public
    function actionUpdateQuantity($itemId, $action = null, $quantity = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $cartKey = Cart::getCartKey();
        $cart = Cart::getItems($cartKey) ?: [];
        $uniqueKey = 'product_' . $itemId;

        if (!isset($cart[$uniqueKey])) {
            return ['success' => false, 'error' => 'Item not found in cart.'];
        }

        $product = Product::findOne($itemId);
        if (!$product) {
            return ['success' => false, 'error' => 'Product not found.'];
        }

        $stock = $product->stock;

        if ($quantity !== null) {
            if ($quantity < 1 || $quantity > $stock) {
                return ['success' => false, 'error' => 'Invalid quantity specified.'];
            }
            $cart[$uniqueKey]['quantity'] = $quantity;
        } else {
            $currentQuantity = $cart[$uniqueKey]['quantity'];
            if ($action === 'increment') {
                if ($currentQuantity < $stock) {
                    $cart[$uniqueKey]['quantity']++;
                } else {
                    return ['success' => false, 'error' => 'No more stock available.'];
                }
            } elseif ($action === 'decrement') {
                if ($currentQuantity > 1) {
                    $cart[$uniqueKey]['quantity']--;
                } else {
                    return ['success' => false, 'error' => 'Minimum quantity is 1.'];
                }
            }
        }

        Cart::setItem($cartKey, $cart);
        $totalCost = Cart::getTotalCost();

        return [
            'success' => true,
            'newQuantity' => $cart[$uniqueKey]['quantity'],
            'newTotal' => Yii::$app->formatter->asCurrency($cart[$uniqueKey]['price'] * $cart[$uniqueKey]['quantity'], 'EUR'),
            'newCartTotal' => Yii::$app->formatter->asCurrency($totalCost), 'EUR',
        ];
    }
}

