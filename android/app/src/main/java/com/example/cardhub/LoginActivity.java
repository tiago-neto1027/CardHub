package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.example.cardhub.utils.UserUtils;

public class LoginActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        UserUtils userUtils = new UserUtils();
        if (userUtils.isLoggedIn(getApplicationContext())) {
            navigateToAppMainActivity();
        } else if (savedInstanceState == null) {
            loadFragment(new LoginFragment());
        }
    }

    public void loadFragment(Fragment fragment) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        fragmentManager.beginTransaction()
                .replace(R.id.fragment_container, fragment)
                .addToBackStack(null)
                .commit();
    }

    public void navigateToAppMainActivity() {
        Intent intent = new Intent(LoginActivity.this, AppMainActivity.class);
        startActivity(intent);
        finish();
    }
}