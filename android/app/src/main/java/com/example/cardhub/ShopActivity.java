package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.GravityCompat;
import androidx.appcompat.widget.Toolbar;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.example.cardhub.controllers.CardController;
import com.google.android.material.navigation.NavigationView;
import com.google.android.material.bottomnavigation.BottomNavigationView;


import models.Card;

public class ShopActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    public static final String CARD_ID = "CARD_ID";

    private CardController cardController;

    private FragmentManager fragmentManager;
    private DrawerLayout drawer;
    private  BottomNavigationView bottomNavigationView;
    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_shop);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        drawer = findViewById(R.id.drawerLayout);
        bottomNavigationView = findViewById(R.id.bottom_navigation);

        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.open, R.string.close);
        toggle.syncState();
        drawer.addDrawerListener(toggle);

        cardController = new CardController(this);
        fragmentManager = getSupportFragmentManager();

        bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.nav_shop);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onNavigationItemSelected);
        int cardId = getIntent().getIntExtra(CARD_ID, -1);
        if (cardId != -1) {
            showListingsFragment(cardId);
        } else{
            loadInitialFragment();
        }
    }

    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_home) {
            //intent = new Intent(this, SettingsActivity.class);
            setTitle(item.getTitle());
        } else if (item.getItemId() == R.id.nav_wishlist) {
            // Launch Wishlist Activity
            //intent = new Intent(this, WishlistActivity.class);
            setTitle(item.getTitle());
        } else if (item.getItemId() == R.id.nav_shop) {
            intent = new Intent(this, ShopActivity.class);
            setTitle(item.getTitle());
        } else if (item.getItemId() == R.id.nav_profile) {
            //intent = new Intent(this, ProfileActivity.class);
            setTitle(item.getTitle());
        }

        if (intent != null) {
            finish();
            startActivity(intent);
            return true;
        }
        return false;
    }

    private boolean loadInitialFragment(){
        Menu menu = bottomNavigationView.getMenu();
        MenuItem item = menu.getItem(0);
        item.setCheckable(true);
        return onNavigationItemSelected(item);
    }

    private void showListingsFragment(int cardId) {
        ListingsFragment listingsFragment = new ListingsFragment();
        Bundle bundle = new Bundle();
        bundle.putInt(ListingsFragment.CARD_ID, cardId);
        listingsFragment.setArguments(bundle);

        Card tempCard = cardController.fetchCardDB(cardId);
        setTitle(tempCard.getName());

        fragmentManager.beginTransaction()
                .replace(R.id.contentFragment, listingsFragment)
                .commit();
    }

    @Override
    public void onPointerCaptureChanged(boolean hasCapture) {
        super.onPointerCaptureChanged(hasCapture);
    }
}
