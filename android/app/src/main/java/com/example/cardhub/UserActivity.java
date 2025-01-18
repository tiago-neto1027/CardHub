package com.example.cardhub;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.cardhub.controllers.UserController;
import com.example.cardhub.utils.UserUtils;
import com.google.android.material.bottomnavigation.BottomNavigationView;

public class UserActivity extends AppCompatActivity {

    private TextView toolbarTitle;
    private UserUtils userUtils;
    private UserController userController;

    private EditText etNewEmail;
    private EditText etNewUsername;
    private TextView tvEmail;
    private TextView tvUsername;

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
        tvUsername = findViewById(R.id.tvUsername);
        tvEmail = findViewById(R.id.tvEmail);
        etNewUsername = findViewById(R.id.etNewUsername);
        etNewEmail = findViewById(R.id.etNewEmail);

        // Set user data
        userUtils = new UserUtils();
        tvUsername.setText(userUtils.getUsername(this));
        userController = new UserController(this);
        userController.getEmail(new UserController.UserEmailCallback() {
            @Override
            public void onEmailReceived(String email) {
                tvEmail.setText(email);
            }
            @Override
            public void onError(String error) {
                Toast.makeText(UserActivity.this, error, Toast.LENGTH_SHORT).show();
            }
        });
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

    public void updateUsername(View view) {
        String newUsername = etNewUsername.getText().toString().trim();
        if (newUsername.isEmpty()) {
            Toast.makeText(this, "Please enter an username.", Toast.LENGTH_SHORT).show();
            return;
        }

        userController.updateUsername(newUsername, new UserController.UserUsernameCallback() {
            @Override
            public void onUsernameReceived(String message) {
                Toast.makeText(UserActivity.this, "Username updated successfully!", Toast.LENGTH_SHORT).show();
                tvUsername.setText(message);
            }

            @Override
            public void onError(String error) {
                Toast.makeText(UserActivity.this, error, Toast.LENGTH_SHORT).show();
            }
        });
    }


    public void updateEmail(View view) {
        String newEmail = etNewEmail.getText().toString().trim();
        if (newEmail.isEmpty()) {
            Toast.makeText(this, "Please enter an email.", Toast.LENGTH_SHORT).show();
            return;
        }

        userController.updateEmail(newEmail, new UserController.UserEmailCallback() {
            @Override
            public void onEmailReceived(String message) {
                Toast.makeText(UserActivity.this, "Email updated successfully!", Toast.LENGTH_SHORT).show();
                tvEmail.setText(message);
            }

            @Override
            public void onError(String error) {
                Toast.makeText(UserActivity.this, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void logOut(View view) {
        userController.logOut();

        Intent intent = new Intent(this, LoginActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(intent);
        finish();
    }
}