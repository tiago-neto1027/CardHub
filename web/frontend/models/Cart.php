<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class Cart
{
    const CACHE_KEY = 'shopping_cart';

    /**
     * Get all cart items.
     *
     * @return array
     */
    public static function getItems()
    {
        return Yii::$app->cache->get(self::CACHE_KEY) ?: [];
    }

    /**
     * Add item to the cart.
     *
     * @param int $productId
     * @param int $quantity
     */
    public static function addItem($productId, $quantity = 1)
    {
        $cart = self::getItems();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        Yii::$app->cache->set(self::CACHE_KEY, $cart);
    }

    /**
     * Remove item from the cart.
     *
     * @param int $productId
     */
    public static function removeItem($productId)
    {
        $cart = self::getItems();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Yii::$app->cache->set(self::CACHE_KEY, $cart);
        }
    }

    /**
     * Clear all items from the cart.
     */
    public static function clearCart()
    {
        Yii::$app->cache->delete(self::CACHE_KEY);
    }


}