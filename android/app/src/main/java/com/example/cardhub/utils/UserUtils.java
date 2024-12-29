package com.example.cardhub.utils;

import android.content.Context;
import android.content.SharedPreferences;
import android.widget.Toast;
import androidx.security.crypto.EncryptedSharedPreferences;
import androidx.security.crypto.MasterKeys;

public class UserUtils {
    // SharedPreferences
    private static final String SHARED_PREFS_NAME = "AppPreferences";
    private static final String USERNAME_KEY = "Username";
    private static final String PASSWORD_KEY = "Password";

    //Credentials
    private String username;
    private String password;

    //Methods
    private void loadCredentialsFromCache(Context context) {
        try {
            SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                    SHARED_PREFS_NAME,
                    MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC),
                    context,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM);

            username = sharedPreferences.getString(USERNAME_KEY, null);
            password = sharedPreferences.getString(PASSWORD_KEY, null);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void saveCredentialsToCache(Context context, String username, String password) {
        try {
            SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                    SHARED_PREFS_NAME,
                    MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC),
                    context,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM);

            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putString(USERNAME_KEY, username);
            editor.putString(PASSWORD_KEY, password);
            editor.apply();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public boolean isLoggedIn(Context context) {
        loadCredentialsFromCache(context);
        return username != null && password != null;
    }

    public void logout(Context context) {
        try {
            SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                    SHARED_PREFS_NAME,
                    MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC),
                    context,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM);

            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.remove(USERNAME_KEY);
            editor.remove(PASSWORD_KEY);
            editor.apply();

            Toast.makeText(context, "Logged out", Toast.LENGTH_SHORT).show();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void saveCredentials(Context context, String username, String password) {
        saveCredentialsToCache(context, username, password);
    }

    public String getUsername(Context context) {
        if (username == null) {
            loadCredentialsFromCache(context);
        }
        return username;
    }

    public String getPassword(Context context) {
        if (password == null) {
            loadCredentialsFromCache(context);
        }
        return password;
    }
}
