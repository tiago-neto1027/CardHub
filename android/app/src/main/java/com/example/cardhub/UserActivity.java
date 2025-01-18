package com.example.cardhub;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.cardhub.utils.UserUtils;
import com.google.android.material.bottomnavigation.BottomNavigationView;

public class UserActivity extends AppCompatActivity {

    private TextView toolbarTitle;
    private UserUtils userUtils;

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

        // Initialize Views
        TextView tvUsername = findViewById(R.id.tvUsername);
        TextView tvEmail = findViewById(R.id.tvEmail);
        EditText etNewUsername = findViewById(R.id.etNewUsername);
        EditText etNewEmail = findViewById(R.id.etNewEmail);

        // Set user data (Replace these with actual user data)
        userUtils = new UserUtils();
        tvUsername.setText(userUtils.getUsername(this));
        tvEmail.setText("user@example.com");
    }

    //Bottom navigation
    public boolean onBottomNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_home) {
            intent = new Intent(this, HomeActivity.class);
        } else if (item.getItemId() == R.id.nav_wishlist) {
            intent = new Intent(this, FavoriteActivity.class);
        } else if (item.getItemId() == R.id.nav_shop) {
            intent = new Intent(this, ShopActivity.class);
        }
        if (intent != null) {
            startActivity(intent);
            finish();
            return true;
        }
        return false;
    }

    //Settings Button
    public void navigateToSettings(View view) {
        Intent intent = new Intent(UserActivity.this, SettingsActivity.class);
        startActivity(intent);
    }
}