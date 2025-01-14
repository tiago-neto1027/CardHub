package com.example.cardhub.controllers;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.example.cardhub.listeners.CardsListener;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import models.Card;
import models.RestAPIClient;

public class CardController {
    private final Context context;
    private CardsListener cardsListener;

    public CardController(Context context){
        this.context = context;
    }
    public void setCardsListener(CardsListener listener) {
        this.cardsListener = listener;
    }

    public Card parseCard(JSONObject cardJson) throws JSONException {
        return new Card(
                cardJson.getInt("id"),
                cardJson.getInt("game_id"),
                cardJson.getString("name"),
                cardJson.getString("rarity"),
                cardJson.getString("image_url"),
                cardJson.getString("status"),
                cardJson.isNull("description") ? null : cardJson.optString("description"),
                cardJson.getInt("created_at"),
                cardJson.getInt("updated_at"),
                cardJson.isNull("user_id") ? null : cardJson.optInt("user_id")
        );
    }

    /*
    Fetches all the cards from the API

    This checks the internet connection, if the user has internet, it grabs the cards from the api
    If the user doesn't have internet then it loads them from the local database
     */
    public void fetchCards() {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();
            // TODO: Load cards from local database instead
            return;
        }

        RestAPIClient.getInstance(context).getRequest(Endpoints.CARD_ENDPOINT, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    JSONArray cardsArray = response.getJSONArray("object");
                    ArrayList<Card> cardsList = new ArrayList<>();

                    for (int i = 0; i < cardsArray.length(); i++) {
                        JSONObject cardJson = cardsArray.getJSONObject(i);
                        Card card = parseCard(cardJson);
                        cardsList.add(card);
                    }

                    if (cardsListener != null) {
                        cardsListener.onRefreshCardsList(cardsList);
                    }
                } catch (JSONException e) {
                    Log.e("CardController", "Error parsing cards data", e);
                    Toast.makeText(context, e.toString(), Toast.LENGTH_SHORT).show();
                }
            }
            @Override
            public void onError(String error) {
                Log.e("CardController", "Error fetching cards: " + error);
                Toast.makeText(context, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    /*
    Fetches a single card By it's ID

    It tries to fetch the card in the local database first,
    If it doesn't exist in the local database it requests it to the API
     */
    public void fetchSingleCard(int cardId, final RestAPIClient.APIResponseCallback callback){
        // TODO: Load card from local database if available

        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();
            return;
        }

        String endpoint = Endpoints.CARD_ENDPOINT + "/" + cardId;
        RestAPIClient.getInstance(context).getRequestObject(endpoint, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                callback.onSuccess(response);
            }

            @Override
            public void onError(String error) {
                Log.d("CardController", "SingleCard onError: " + error);
                Toast.makeText(context, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    /*
    Fetches the count of Listings for a single Card

    This checks the internet connection, if the user has internet, it grabs the countlistings from the api
    If the user doesn't have internet then it loads them from the local database
    This happens in order to keep the user up to date in the amount of listings for a card
    */
    public void fetchCountListings(int cardId, final RestAPIClient.APIResponseCallback callback) {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();
            // TODO: Load countlistings from local database instead
            return;
        }

        String endpoint = Endpoints.CARD_ENDPOINT + "/" + cardId + "/countlistings";
        RestAPIClient.getInstance(context).getRequestObject(endpoint, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                callback.onSuccess(response);
            }

            @Override
            public void onError(String error) {
                Log.d("CardController", "Fetch CountListings onError: " + error);
                Toast.makeText(context, error, Toast.LENGTH_SHORT).show();
            }
        });
    }
}
