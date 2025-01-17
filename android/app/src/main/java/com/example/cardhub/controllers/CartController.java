package com.example.cardhub.controllers;

import android.content.Context;
import android.widget.Toast;

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
        if(type.equals("listing"))
        {
            if(cardHubDBHelper.isItemInCart(cartItem))
                Toast.makeText(context, "The item already is in the cart.", Toast.LENGTH_SHORT).show();
            else {
                cardHubDBHelper.insertCartItem(cartItem);
                Toast.makeText(context, "Item added to the cart.", Toast.LENGTH_SHORT).show();
            }
        }
        else if (type.equals("product"))
        {
            if(cardHubDBHelper.isItemInCart(cartItem)){
                String action = "plus";
                cardHubDBHelper.updateCartQuantity(cartItem, action);
                Toast.makeText(context, "Quantity updated in the cart.", Toast.LENGTH_SHORT).show();
            }
            else {
                cardHubDBHelper.insertCartItem(cartItem);
                Toast.makeText(context, "Item added to the cart.", Toast.LENGTH_SHORT).show();
            }
        }
    }
}
