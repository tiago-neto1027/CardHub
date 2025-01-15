package com.example.cardhub;

import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;

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
import com.example.cardhub.utils.UserUtils;
import com.google.android.material.navigation.NavigationView;

import models.Card;

public class AppMainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    public static final String USERNAME = "USERNAME";
    public static final String CARD_ID = "CARD_ID";

    private CardController cardController;

    private FragmentManager fragmentManager;
    private DrawerLayout drawer;
    private NavigationView navigationView;

    public String username;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_app_main);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        drawer = findViewById(R.id.drawerLayout);
        navigationView = findViewById(R.id.navView);

        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.open, R.string.close);
        toggle.syncState();
        drawer.addDrawerListener(toggle);
        navigationView.setNavigationItemSelectedListener(this);

        cardController = new CardController(this);
        fragmentManager = getSupportFragmentManager();

        int cardId = getIntent().getIntExtra(CARD_ID, -1);
        if (cardId != -1) {
            showListingsFragment(cardId);
        } else{
            loadInitialFragment();
        }
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Fragment fragment = null;

        if(item.getItemId() == R.id.cardsList) {
            fragment = new CardsFragment();
            setTitle(item.getTitle());
        }
        if(item.getItemId() == R.id.listingsList) {
            fragment = new ListingsFragment();
            setTitle(item.getTitle());
        }
        //TODO: Add new fragments here

        if(fragment != null)
            fragmentManager.beginTransaction().replace(R.id.contentFragment,fragment).commit();
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    private boolean loadInitialFragment(){
        Menu menu = navigationView.getMenu();
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
