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

                    Log.d("FavoriteController", "onSuccess: " + favorites.length());
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
}
