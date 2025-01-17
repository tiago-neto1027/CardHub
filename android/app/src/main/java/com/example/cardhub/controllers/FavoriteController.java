package com.example.cardhub.controllers;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

import models.CardHubDBHelper;
import models.RestAPIClient;

public class FavoriteController {
    private Context context;
    private CardHubDBHelper dbHelper;

    public FavoriteController(Context context) {
        this.context = context;
        this.dbHelper = CardHubDBHelper.getInstance(context);
    }

    public void loadFavorites() {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();
            return;
        }

        RestAPIClient.getInstance(context).getRequest(Endpoints.FAVORITE_ENDPOINT, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    JSONArray favorites = response.getJSONArray("object");

                    dbHelper.removeAllFavorites();

                    if (favorites.length() == 0) {
                        return;
                    }

                    for (int i = 0; i < favorites.length(); i++) {
                        JSONObject favoriteObject = favorites.getJSONObject(i);
                        int cardId = favoriteObject.getInt("card_id");
                        dbHelper.insertFavorite(cardId);
                    }
                } catch (JSONException e) {
                    Log.e("FavoriteController", "Error parsing favorites data", e);
                    Toast.makeText(context, e.toString(), Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onError(String error) {
                Log.e("FavoriteController", "Error fetching favorites: " + error);
                Toast.makeText(context, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    public List<Integer> fetchFavoritesDB(){
        return dbHelper.getAllFavorites();
    }

    public boolean  isFavorite(int cardId) {
        return dbHelper.isFavorite(cardId);
    }

    public void removeFavorite(int cardId) {
        dbHelper.removeFavorite(cardId);
        //Sends to the API
        String endpoint = Endpoints.FAVORITE_ENDPOINT + "/" + cardId;
        RestAPIClient.getInstance(context).deleteRequest(endpoint, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                Log.d("Favorite", "Favorite removed successfully");
            }

            @Override
            public void onError(String error) {
                Log.e("Favorite", "Error removing favorite: " + error);
            }
        });
    }

    public void insertFavorite(int cardId) {
        try {
            dbHelper.insertFavorite(cardId);
            //Creates Json
            JSONObject postData = new JSONObject();
            postData.put("card_id", cardId);

            //Sends to the API
            RestAPIClient.getInstance(context).postRequest(Endpoints.FAVORITE_ENDPOINT, postData, new RestAPIClient.APIResponseCallback() {
                @Override
                public void onSuccess(JSONObject response) {
                    Log.d("Favorite", "Favorite added successfully");
                }

                @Override
                public void onError(String error) {
                    Toast.makeText(context, "Error adding favorite: ", Toast.LENGTH_SHORT).show();
                    Log.e("Favorite", "Error adding favorite: " + error);
                }
            });
        } catch (JSONException e) {
            Log.e("Favorite", "Error creating JSON to add favorite", e);
        }
    }
}
