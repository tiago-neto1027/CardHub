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
        $uniqueKey = $type . '_' . $itemId;

        if (isset($cartItems[$uniqueKey])) {
            if ($type === 'listing') {
                Yii::$app->session->setFlash('warning', 'This card is already in the cart.');
            } elseif ($type === 'product' && $cartItems[$uniqueKey]['quantity'] >= $stock) {
                Yii::$app->session->setFlash('warning', 'Not enough items in stock.');
            } elseif ($type === 'product') {
                $cartItems[$uniqueKey]['quantity'] += $quantity;
                Yii::$app->session->setFlash('success', ucfirst($type) . ' added to cart.');
                self::setItem($cartKey, $cartItems);
            }
        } else {
            $cartItems[$uniqueKey] = [
                'itemId' => $itemId,
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
    public static function removeItem($type, $itemId)
    {
        $cartKey = self::getCartKey();
        $uniqueKey = $type . '_' . $itemId;


        $cart = self::getItems($cartKey);
        if (isset($cart[$uniqueKey])) {
            unset($cart[$uniqueKey]);
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

