package com.example.cardhub;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.MenuItem;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.app.AppCompatDelegate;
import androidx.appcompat.widget.Toolbar;
import androidx.viewpager2.widget.ViewPager2;

import com.example.cardhub.adapters.ProductCarouselAdapter;
import com.example.cardhub.controllers.ProductController;
import com.example.cardhub.listeners.ProductsListener;
import com.example.cardhub.controllers.FavoriteController;

import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.util.ArrayList;

import models.Product;


public class HomeActivity extends AppCompatActivity {
    private BottomNavigationView bottomNavigationView;
    private ViewPager2 productCarousel;
    private ProductController productController;
    private SharedPreferences sharedPreferences;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        //Top bar with title and buttons
        Toolbar toolbar = findViewById(R.id.toolbar);
        setTitle(null);
        TextView toolbarTitle = findViewById(R.id.toolbar_title);
        toolbarTitle.setText(R.string.cardhub);
        setSupportActionBar(toolbar);

        //Apply the correct theme based on the user's preference
        sharedPreferences = getSharedPreferences("ThemePrefs", MODE_PRIVATE);
        boolean isDarkMode = sharedPreferences.getBoolean("isDarkMode", false);
        applyTheme(isDarkMode);

        bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.nav_home);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onNavigationItemSelected);

        //Loads Favorites to DB
        FavoriteController favoriteController = new FavoriteController(getApplicationContext());
        favoriteController.loadFavorites();
      
        //Page Content
        productCarousel = findViewById(R.id.product_carousel);
        productController = new ProductController(this);

        productController.setProductsListener(new ProductsListener() {
            @Override
            public void onRefreshProductList(ArrayList<Product> productList) {
                ProductCarouselAdapter adapter = new ProductCarouselAdapter(HomeActivity.this, productList);
                productCarousel.setAdapter(adapter);
            }
        });

        productController.fetchProducts();
    }

    /**
     * Handle BottomNavigationView item selection
     */
    private boolean onNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_wishlist) {
            intent = new Intent(this, FavoriteActivity.class);
        } else if (item.getItemId() == R.id.nav_shop) {
            intent = new Intent(this, ShopActivity.class);
        } else if (item.getItemId() == R.id.nav_profile) {
            intent = new Intent(this, CartActivity.class);
        }
        if (intent != null) {
            startActivity(intent);
            finish();
            return true;
        }
        return false;
    }

    private void applyTheme(boolean isDarkMode) {
        if (isDarkMode) {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
        } else {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
        }
    }
}
