package com.example.cardhub.listeners;

import java.util.ArrayList;

import models.Listing;

public interface ListingsListener {
    void onRefreshListingsList(ArrayList<Listing> listingsList);
}
