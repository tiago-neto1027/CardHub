package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.cardhub.utils.UserUtils;

import org.json.JSONObject;

import models.RestAPIClient;

public class LoginActivity extends AppCompatActivity {

    UserUtils userUtils = new UserUtils();

    private EditText etUsername, etPassword;
    private RestAPIClient apiClient;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        etUsername = findViewById(R.id.etUsername);
        etPassword = findViewById(R.id.etPassword);

        Button btnLogin = findViewById(R.id.btnLogin);
        Button btnSignup = findViewById(R.id.btnSignUp);
        ImageButton ibtnSettings = findViewById(R.id.ibtnSettings);

        apiClient = RestAPIClient.getInstance(this);

        // Check if the user is already logged in
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

        apiClient.loginAPI(username, password, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                navigateToMainScreen();
            }

            @Override
            public void onError(String error) {
                Toast.makeText(LoginActivity.this, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void navigateToMainScreen() {
        Intent intent = new Intent(LoginActivity.this, AppMainActivity.class);
        intent.putExtra(AppMainActivity.USERNAME, etUsername.getText().toString());
        startActivity(intent);
        finish();
    }

    public void onClickSignup(View view) {
        // Navigate to the signup page (if needed)
        Toast.makeText(this, "Navigate to signup", Toast.LENGTH_SHORT).show();
    }

    public void onClickSettings(View view) {
        // Navigate to settings page (for changing IP or other settings)
        Toast.makeText(this, "Navigate to settings", Toast.LENGTH_SHORT).show();
    }
}