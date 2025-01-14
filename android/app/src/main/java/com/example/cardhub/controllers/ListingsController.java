package com.example.cardhub.controllers;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.example.cardhub.listeners.ListingsListener;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import models.Card;
import models.CardHubDBHelper;
import models.Listing;
import models.RestAPIClient;

public class ListingsController {
    private final Context context;
    private ListingsListener listingsListener;
    private ArrayList<Listing> localListings;
    private CardHubDBHelper cardHubDBHelper = null;

    public ListingsController(Context context){
        this.context = context;
        cardHubDBHelper = CardHubDBHelper.getInstance(context);
        localListings = new ArrayList<>();
    }

    public void setListingsListener(ListingsListener listener){
        this.listingsListener = listener;
    }

    public Listing parseListing(JSONObject listingJson) throws JSONException {
        return new Listing(
                listingJson.getInt("id"),
                listingJson.getInt("seller_id"),
                listingJson.getString("seller_username"),
                listingJson.getInt("card_id"),
                listingJson.getString("card_name"),
                listingJson.getString("card_image_url"),
                listingJson.getDouble("price"),
                listingJson.getString("condition"),
                listingJson.getString("status"),
                listingJson.getInt("created_at"),
                listingJson.getInt("updated_at")
        );
    }

    /*
    Fetches all the listings from the API

    This checks the internet connection, if the user has internet, it grabs the listings from the api
    If the user doesn't have internet then it loads them from the local database
     */
    public void fetchListings() {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();

            if (listingsListener != null) {
                listingsListener.onRefreshListingsList(cardHubDBHelper.getAllListings());
            }
            return;
        }

        RestAPIClient.getInstance(context).getRequest(Endpoints.LISTING_ENDPOINT, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    JSONArray listingsArray = response.getJSONArray("object");
                    ArrayList<Listing> listingsList = new ArrayList<>();

                    cardHubDBHelper.removeAllListings();

                    for (int i = 0; i < listingsArray.length(); i++) {
                        JSONObject listingJson = listingsArray.getJSONObject(i);
                        Listing listing = parseListing(listingJson);
                        listingsList.add(listing);
                        cardHubDBHelper.insertListing(listing);
                    }

                    if (listingsListener != null) {
                        listingsListener.onRefreshListingsList(listingsList);
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
}
