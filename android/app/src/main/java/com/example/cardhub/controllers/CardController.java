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
import models.CardHubDBHelper;
import models.RestAPIClient;

public class CardController {
    private final Context context;
    private CardsListener cardsListener;
    private CardHubDBHelper cardHubDBHelper = null;

    public CardController(Context context){
        this.context = context;
        cardHubDBHelper = CardHubDBHelper.getInstance(context);
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

            if (cardsListener != null) {
                cardsListener.onRefreshCardsList(cardHubDBHelper.getAllCards());
            }
            return;
        }

        RestAPIClient.getInstance(context).getRequest(Endpoints.CARD_ENDPOINT, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    JSONArray cardsArray = response.getJSONArray("object");
                    ArrayList<Card> cardsList = new ArrayList<>();

                    cardHubDBHelper.removeAllCards();

                    for (int i = 0; i < cardsArray.length(); i++) {
                        JSONObject cardJson = cardsArray.getJSONObject(i);
                        Card card = parseCard(cardJson);
                        cardsList.add(card);
                        cardHubDBHelper.insertCard(card);
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
    Fetches a single card

    This method tries to fetch a single card from the local database and sends it back as a json
    If it isn't able to find the card in the database then
    It checks the internet connection, and if it exists, asks the API for the card
     */
    public void fetchSingleCard(int cardId, final RestAPIClient.APIResponseCallback callback) {
        Card localCard = cardHubDBHelper.getCardById(cardId);
        if (localCard != null) {
            try {
                JSONObject localCardJson = new JSONObject();
                localCardJson.put("id", localCard.getId());
                localCardJson.put("game_id", localCard.getGameId());
                localCardJson.put("name", localCard.getName());
                localCardJson.put("rarity", localCard.getRarity());
                localCardJson.put("image_url", localCard.getImageUrl());
                localCardJson.put("status", localCard.getStatus());
                localCardJson.put("description", localCard.getDescription());
                localCardJson.put("created_at", localCard.getCreatedAt());
                localCardJson.put("updated_at", localCard.getUpdatedAt());
                localCardJson.put("user_id", localCard.getUserId());

                callback.onSuccess(localCardJson);
                return;
            } catch (JSONException e) {
                Log.e("CardController", "Error converting local card to JSON", e);
            }
        }

        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();
            return;
        }

        String endpoint = Endpoints.CARD_ENDPOINT + "/" + cardId;
        RestAPIClient.getInstance(context).getRequestObject(endpoint, callback);
    }

    /*
    Fetches the count of Listings for a single Card

    If the countListings exists in the local database, it grabs and returns it instantly
    Afterwards, this checks the internet connection and fetches the countListings from the api, sending it back again

    This way, the countListings is immediately loaded to the user if it exists
    But it can be updated if the API has a different listingCount
    */
    public void fetchCountListings(int cardId, final RestAPIClient.APIResponseCallback callback) {
        Card localCard = cardHubDBHelper.getCardById(cardId);
        if (localCard != null && localCard.getCountListings() != null) {
            try {
                JSONObject countListingsJson = new JSONObject();
                countListingsJson.put("listingCount", localCard.getCountListings());

                callback.onSuccess(countListingsJson);
            } catch (JSONException e) {
                Log.e("CardController", "Error converting countListings to JSON", e);
            }
        }

        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.\nUnable to get latest listings", Toast.LENGTH_SHORT).show();
            return;
        }

        String endpoint = Endpoints.CARD_ENDPOINT + "/" + cardId + "/countlistings";
        RestAPIClient.getInstance(context).getRequestObject(endpoint, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    int countListings = response.getInt("listingCount");

                    Card updatedCard = cardHubDBHelper.getCardById(cardId);
                    if (updatedCard != null) {
                        updatedCard.setCountListings(countListings);
                        cardHubDBHelper.updateCard(updatedCard);
                    }

                    callback.onSuccess(response);
                } catch (JSONException e) {
                    Log.e("CardController", "Error parsing countListings from API", e);
                    callback.onError("Error parsing API response");
                }
            }

            @Override
            public void onError(String error) {
                callback.onError(error);
            }
        });
    }

    public Card fetchCardDB(int cardId){
        return cardHubDBHelper.getCardById(cardId);
    }
}
