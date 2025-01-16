package com.example.cardhub.utils;

import android.content.Context;
import android.content.SharedPreferences;

public class Endpoints {
    //Shared Preferences BASE_URL
    private static final String PREFERENCES_NAME = "com.example.cardhub.PREFERENCES";
    private static final String BASE_URL_KEY = "BASE_URL";
    private static final String DEFAULT_BASE_URL = "http://13.39.156.210:8080/api";

    public static final String LOGIN_ENDPOINT = "/auth/login";
    public static final String SIGNUP_ENDPOINT = "/auth/signup";


    public static final String CARD_ENDPOINT = "/cards";

    public static final String PRODUCT_ENDPOINT = "/products";

    public static final String LISTING_ENDPOINT = "/listings";

    public static final String FAVORITE_ENDPOINT = "/favorites";


    //Methods
    public static String getBaseUrl(Context context) {
        SharedPreferences prefs = context.getSharedPreferences(PREFERENCES_NAME, Context.MODE_PRIVATE);

        return prefs.getString(BASE_URL_KEY, DEFAULT_BASE_URL);
    }

    public static void setBaseUrl(Context context, String baseUrl) {
        SharedPreferences prefs = context.getSharedPreferences(PREFERENCES_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putString(BASE_URL_KEY, baseUrl);
        editor.apply();
    }
}
