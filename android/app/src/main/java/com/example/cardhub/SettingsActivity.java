package com.example.cardhub;

import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.cardhub.utils.Endpoints;

public class SettingsActivity extends AppCompatActivity {

    private EditText etURL;
    private Button btnSave;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_settings);

        etURL = findViewById(R.id.etURL);
        btnSave = findViewById(R.id.btnSave);

        etURL.setText(Endpoints.getBaseUrl(getApplicationContext()));
    }

    public void onClickSave(View view) {
        String newBaseUrl = etURL.getText().toString().trim();

        if(isValidUrl(newBaseUrl)){
            Endpoints.setBaseUrl(getApplicationContext(), newBaseUrl);
            finish();
        }
        else
            Toast.makeText(this, "Invalid URL", Toast.LENGTH_SHORT).show();
    }

    private boolean isValidUrl(String url) {
        return !TextUtils.isEmpty(url) && android.util.Patterns.WEB_URL.matcher(url).matches();
    }
}