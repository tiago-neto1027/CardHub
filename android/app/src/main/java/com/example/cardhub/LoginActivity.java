package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

public class LoginActivity extends AppCompatActivity {

private EditText etUsername, etPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_login);

        etUsername = findViewById(R.id.etUsername);
        etPassword = findViewById(R.id.etPassword);

        Button btnLogin = findViewById(R.id.btnLogin);
        Button btnSignup = findViewById(R.id.btnSignUp);
        ImageButton ibtnSettings = findViewById(R.id.ibtnSettings);

        btnLogin.setOnClickListener(this::onClickLogin);
        btnSignup.setOnClickListener(this::onClickSignup);
        ibtnSettings.setOnClickListener(this::onClickSettings);
    };

    public void onClickLogin(View view) {
        //TODO validate if user exists and matches the input


        Toast.makeText(this, "Dados válidos", Toast.LENGTH_SHORT).show();


        Intent intent = new Intent(LoginActivity.this,AppMainActivity.class);
        intent.putExtra(AppMainActivity.USERNAME,etUsername.getText().toString());
        startActivity(intent);
        /*} else {
            Toast.makeText(this, "Something went wrong", Toast.LENGTH_SHORT).show();
        }*/
    }

    public void onClickSignup(View view) {
        //TODO send to the signup page
    }

    public void onClickSettings(View view) {
        //TODO send to the settings page, this should allow to change the IP
    }
}

