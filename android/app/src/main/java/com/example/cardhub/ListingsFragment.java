package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.cardhub.adapters.ListingAdapter;
import com.example.cardhub.controllers.ListingsController;
import com.example.cardhub.listeners.ListingsListener;

import java.util.ArrayList;

import models.Listing;

public class ListingsFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ListingsListener {

    private ListView lvListings;
    private SearchView searchView;
    private SwipeRefreshLayout swipeRefreshLayout;
    private ListingsController listingsController;

    public ListingsFragment(){
        //Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstaceState){
        View view = inflater.inflate(R.layout.fragment_listings, container, false);
        setHasOptionsMenu(true);

        lvListings = view.findViewById(R.id.lvListings);
        lvListings.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Intent intent = new Intent(getContext(), ListingDetailsActivity.class);
                intent.putExtra(ListingDetailsActivity.LISTING_ID, (int) l);
                startActivity(intent);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);

        listingsController = new ListingsController(getContext());
        listingsController.setListingsListener(this);
        listingsController.fetchListings();

        return view;
    }

    //TODO: Instead of the search in the Cards Fragment, add a button that orders the listings by price

    @Override
    public void onRefresh() {
        listingsController.fetchListings();
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshListingsList(ArrayList<Listing> listingsList) {
        if(listingsList != null){
            lvListings.setAdapter(new ListingAdapter(listingsList, getContext()));
        }
    }
}
