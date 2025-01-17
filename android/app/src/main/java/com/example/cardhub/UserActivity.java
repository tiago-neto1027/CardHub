package com.example.cardhub;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.android.material.bottomnavigation.BottomNavigationView;

public class UserActivity extends AppCompatActivity {

    private TextView toolbarTitle;

    @SuppressLint("SetTextI18n")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user);

        //Bottom navigation
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.nav_profile);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onBottomNavigationItemSelected);

        //Top navigation
        Toolbar toolbar = findViewById(R.id.toolbar);
        setTitle(null);
        toolbarTitle = findViewById(R.id.toolbar_title);
        toolbarTitle.setText("About Me");
        setSupportActionBar(toolbar);
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
}