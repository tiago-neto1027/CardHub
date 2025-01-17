package com.example.cardhub;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.app.AppCompatDelegate;
import androidx.appcompat.widget.SwitchCompat;

import com.example.cardhub.utils.Endpoints;

public class SettingsActivity extends AppCompatActivity {

    private EditText etURL;
    private SwitchCompat themeSwitch;
    private SharedPreferences sharedPreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_settings);

        // Initialize UI components
        etURL = findViewById(R.id.etURL);
        themeSwitch = findViewById(R.id.themeSwitch);

        // Load Base API URL
        etURL.setText(Endpoints.getBaseUrl(getApplicationContext()));

        // Initialize SharedPreferences to store theme preference
        sharedPreferences = getSharedPreferences("ThemePrefs", MODE_PRIVATE);
        boolean isDarkMode = sharedPreferences.getBoolean("isDarkMode", false);

        // Set the theme switch to match the current theme
        themeSwitch.setChecked(isDarkMode);

        // Apply the correct theme
        applyTheme(isDarkMode);

        //Listener for the Switch Theme
        themeSwitch.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                sharedPreferences.edit().putBoolean("isDarkMode", isChecked).apply();

                applyTheme(isChecked);
            }
        });
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

    private void applyTheme(boolean isDarkMode) {
        if (isDarkMode) {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
        } else {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
        }
    }
}