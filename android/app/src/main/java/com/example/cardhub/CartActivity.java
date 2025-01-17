package com.example.cardhub;

import android.os.Bundle;
import android.widget.ListView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.cardhub.adapters.CartAdapter;

import java.util.ArrayList;

import models.Card;
import models.CardHubDBHelper;
import models.CartItem;

public class CartActivity extends AppCompatActivity {
    private ListView lvCartItems;
    private CardHubDBHelper cardHubDBHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);

        lvCartItems = findViewById(R.id.lvCartItems);
        cardHubDBHelper = CardHubDBHelper.getInstance(getApplicationContext());
        ArrayList<CartItem> cartItemList = cardHubDBHelper.getAllCartItems();

        CartAdapter cartAdapter = new CartAdapter(cartItemList, this);

        lvCartItems.setAdapter(cartAdapter);
    }
}