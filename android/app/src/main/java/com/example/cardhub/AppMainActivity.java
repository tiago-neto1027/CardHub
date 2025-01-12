package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.os.PersistableBundle;
import android.view.MenuItem;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.navigation.NavigationView;

public class AppMainActivity extends AppCompatActivity implements
        NavigationView.OnNavigationItemSelectedListener {

    public static final String USERNAME = "USERNAME";

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState, @Nullable PersistableBundle persistentState) {
        super.onCreate(savedInstanceState, persistentState);
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        return false;
    }

    private void navigateToCards() {
        Intent intent = new Intent(AppMainActivity.this, CardActivity.class);
        startActivity(intent);
        finish();
    }
}
