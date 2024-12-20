<?php

namespace frontend\models;

use common\models\Product;
use Yii;
use yii\base\Model;

class Cart
{
    public static function getItems($cartKey)
    {
        return Yii::$app->cache->get($cartKey) ?: [];
    }

    public static function setItem($cartKey, $cartItems)
    {
        Yii::$app->cache->set($cartKey, $cartItems);
    }

    public static function getCartKey(): string
    {
        $userId = Yii::$app->user->id;
        return 'cart_' . $userId;
    }


    public static function addItemToCart($itemId, $name, $imageUrl, $price, $quantity, $type, $stock)
    {
        $cartKey = self::getCartKey();

        $cartItems = self::getItems($cartKey) ?: [];

        if (isset($cartItems[$itemId])) {
            if ($type === 'card') {
                Yii::$app->session->setFlash('warning', 'This card is already in the cart.');
            } elseif ($type === 'product' && $cartItems[$itemId]['quantity'] >= $stock) {
                Yii::$app->session->setFlash('warning', 'Not enough items in stock.');
            } elseif ($type === 'product') {
                $cartItems[$itemId]['quantity'] += $quantity;
                Yii::$app->session->setFlash('success', ucfirst($type) . ' added to cart.');
                self::setItem($cartKey, $cartItems);
            }
        } else {
            $cartItems[$itemId] = [
                'product_id' => $itemId,
                'name' => $name,
                'image' => $imageUrl,
                'price' => $price,
                'type' => $type,
                'quantity' => $quantity,
            ];
            Yii::$app->session->setFlash('success', ucfirst($type) . ' added to cart.');
            self::setItem($cartKey, $cartItems);
        }
    }



    /**
     * Remove item from the cart.
     *
     * @param int $productId
     */
    public static function removeItem(int $ItemId)
    {
        $cartKey = self::getCartKey();

        $cart = self::getItems($cartKey);
        if (isset($cart[$ItemId])) {
            unset($cart[$ItemId]);
            self::setItem($cartKey, $cart);
        }
    }

    /**
     * Clear all items from the cart.
     */
    public static function clearCart()
    {
        $cartKey = self::getCartKey();

        Yii::$app->cache->delete($cartKey);
    }

    public static function getTotalCost()
    {
        $cartKey = self::getCartKey();
        $cart = self::getItems($cartKey);

        $totalCost = 0;
        foreach ($cart as $item) {
            $totalCost += ($item['price'] * $item['quantity']);
        }

        return $totalCost;
    }


}

