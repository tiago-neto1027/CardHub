package com.example.cardhub.utils;

public class Endpoints {
    public static String BASE_URL = "http://13.39.156.210:8080/api";


    public static final String LOGIN_ENDPOINT = "/auth/login";


    public static final String CARD_ENDPOINT = "/cards";
    public static final String CARD_LISTING_COUNT_ENDPOINT = "/cards/countlistings";


    public static final String PRODUCT_ENDPOINT = "/products";


    public static final String LISTING_ENDPOINT = "/listings";


    public static final String USER_ENDPOINT = "/users";


    //Methods
    public String getBaseUrl() {
        return Endpoints.BASE_URL;
    }

    public void setBaseUrl(String baseUrl) {
        Endpoints.BASE_URL = baseUrl;
    }
}
