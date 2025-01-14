package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.cardhub.controllers.AuthController;
import com.example.cardhub.utils.UserUtils;

import org.json.JSONException;
import org.json.JSONObject;

import models.RestAPIClient;

public class LoginActivity extends AppCompatActivity {

    private AuthController authController;

    private EditText etUsername, etPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        authController = new AuthController(this);

        etUsername = findViewById(R.id.etUsername);
        etPassword = findViewById(R.id.etPassword);

        Button btnLogin = findViewById(R.id.btnLogin);
        Button btnSignup = findViewById(R.id.btnSignUp);
        ImageButton ibtnSettings = findViewById(R.id.ibtnSettings);

        // Check if the user is already logged in
        UserUtils userUtils = new UserUtils();
        if (userUtils.isLoggedIn(getApplicationContext())) {
            navigateToMainScreen();
        }

        btnLogin.setOnClickListener(this::onClickLogin);
        btnSignup.setOnClickListener(this::onClickSignup);
        ibtnSettings.setOnClickListener(this::onClickSettings);
    }

    public void onClickLogin(View view) {
        String username = etUsername.getText().toString();
        String password = etPassword.getText().toString();

        if (username.isEmpty() || password.isEmpty()) {
            Toast.makeText(this, "Username and password are required", Toast.LENGTH_SHORT).show();
            return;
        }

        authController.login(username, password, new AuthController.AuthCallback() {
            @Override
            public void onSuccess() {
                navigateToMainScreen();
            }

            @Override
            public void onFailure(String errorMessage) {
                Toast.makeText(LoginActivity.this, errorMessage, Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void onClickSignup(View view) {
        //TODO: Navigate to the signup page (if needed)
        Toast.makeText(this, "Navigate to signup", Toast.LENGTH_SHORT).show();
    }

    public void onClickSettings(View view) {
        Intent intent = new Intent(this, SettingsActivity.class);
        startActivity(intent);
    }

    private void navigateToMainScreen() {
        Intent intent = new Intent(LoginActivity.this, AppMainActivity.class);
        intent.putExtra(AppMainActivity.USERNAME, etUsername.getText().toString());
        startActivity(intent);
        finish();
    }
}