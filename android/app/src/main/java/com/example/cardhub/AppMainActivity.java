package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.example.cardhub.controllers.CardController;
import com.example.cardhub.controllers.FavoriteController;
import com.google.android.material.navigation.NavigationView;
import com.google.android.material.bottomnavigation.BottomNavigationView;


public class AppMainActivity extends AppCompatActivity {
    private FragmentManager fragmentManager;
    private BottomNavigationView bottomNavigationView;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_app_main);
        setTitle("Home");

        fragmentManager = getSupportFragmentManager();

        bottomNavigationView = findViewById(R.id.bottom_navigation);

        bottomNavigationView.setSelectedItemId(R.id.nav_home);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onNavigationItemSelected);

    }

    /**
     * Handle BottomNavigationView item selection
     */
    private boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_home) {
            intent = new Intent(this, SettingsActivity.class);
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


}
