package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;

import androidx.annotation.MainThread;
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


import models.Card;public class ShopActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    public static final String CARD_ID = "CARD_ID";

    private CardController cardController;
    private FragmentManager fragmentManager;
    private DrawerLayout drawer;
    private NavigationView navigationView;
    private TextView toolbarTitle;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_shop);

        //Top bar with title and buttons
        Toolbar toolbar = findViewById(R.id.toolbar);
        setTitle(null);
        toolbarTitle = findViewById(R.id.toolbar_title);
        setSupportActionBar(toolbar);

        drawer = findViewById(R.id.drawerLayout);
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);

        //Drawer to go to other fragments
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.open, R.string.close);
        toggle.syncState();
        drawer.addDrawerListener(toggle);

        cardController = new CardController(this);
        fragmentManager = getSupportFragmentManager();

        navigationView = findViewById(R.id.navView);
        navigationView.setNavigationItemSelectedListener(this);

        bottomNavigationView.setSelectedItemId(R.id.nav_shop);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onBottomNavigationItemSelected);

        int cardId = getIntent().getIntExtra(CARD_ID, -1);
        if (cardId != -1) {
            showListingsFragment(cardId);
        } else {
            loadInitialFragment();
        }
    }

    public boolean onBottomNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_home) {
            intent = new Intent(this, HomeActivity.class);
        } else if (item.getItemId() == R.id.nav_wishlist) {
            intent = new Intent(this, FavoriteActivity.class);
        } else if (item.getItemId() == R.id.nav_profile) {
            intent = new Intent(this, UserActivity.class);
        }
        if (intent != null) {
            startActivity(intent);
            finish();
            return true;
        }
        return false;
    }


    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Fragment fragment = null;

        if (item.getItemId() == R.id.cardsList) {
            fragment = new CardsFragment();
        } else if (item.getItemId() == R.id.productList) {
            fragment = new ProductsFragment();
        } else if (item.getItemId() == R.id.listingsList) {
            fragment = new ListingsFragment();
        }

        if (fragment != null) {
            toolbarTitle.setText(item.getTitle());
            fragmentManager.beginTransaction().replace(R.id.contentFragment, fragment).commit();
        }
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    private boolean loadInitialFragment() {
        Menu menu = navigationView.getMenu();
        MenuItem item = menu.getItem(0);
        item.setChecked(true);
        return onNavigationItemSelected(item);
    }

    private void showListingsFragment(int cardId) {
        ListingsFragment listingsFragment = new ListingsFragment();
        Bundle bundle = new Bundle();
        bundle.putInt(ListingsFragment.CARD_ID, cardId);
        listingsFragment.setArguments(bundle);

        Card tempCard = cardController.fetchCardDB(cardId);

        fragmentManager.beginTransaction()
                .replace(R.id.contentFragment, listingsFragment)
                .commit();
    }

    @Override
    public void onPointerCaptureChanged(boolean hasCapture) {
        super.onPointerCaptureChanged(hasCapture);
    }
}
