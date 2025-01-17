package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.ListView;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.GravityCompat;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.fragment.app.Fragment;

import com.example.cardhub.adapters.CartAdapter;
import com.google.android.material.bottomnavigation.BottomNavigationView;

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

        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);

        lvCartItems = findViewById(R.id.lvCartItems);
        cardHubDBHelper = CardHubDBHelper.getInstance(getApplicationContext());
        ArrayList<CartItem> cartItemList = cardHubDBHelper.getAllCartItems();

        CartAdapter cartAdapter = new CartAdapter(cartItemList, this);

        lvCartItems.setAdapter(cartAdapter);

        bottomNavigationView.isSelected();
        bottomNavigationView.setSelected(false);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onBottomNavigationItemSelected);
    }

    public boolean onBottomNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_home) {
            intent = new Intent(this, HomeActivity.class);
        } else if (item.getItemId() == R.id.nav_shop) {
            intent = new Intent(this, ShopActivity.class);
        } else if (item.getItemId() == R.id.nav_wishlist) {
            intent = new Intent(this, FavoriteActivity.class);
        } else if (item.getItemId() == R.id.nav_profile) {
            //intent = new Intent(this, ProfileActivity.class);
        }
        if (intent != null) {
            startActivity(intent);
            finish();
            return true;
        }
        return false;
    }

    public void onBottomNavigationItemSelected(View view) {
    }
}