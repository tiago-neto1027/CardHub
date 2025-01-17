package com.example.cardhub.controllers;

import android.content.Context;

import models.CardHubDBHelper;
import models.CartItem;

public class CartController
{
    private CardHubDBHelper cardHubDBHelper;
    private Context context;

    public CartController(Context context){
        this.context = context;
        this.cardHubDBHelper = CardHubDBHelper.getInstance(context);
    }

    public void addItemToCart(int itemId, String type, int quantity){
        CartItem cartItem = new CartItem(itemId,type,quantity);
        cardHubDBHelper.insertCartItem(cartItem);
    }
}
