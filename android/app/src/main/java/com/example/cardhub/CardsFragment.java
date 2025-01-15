package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import com.example.cardhub.adapters.CardAdapter;
import com.example.cardhub.controllers.CardController;
import com.example.cardhub.listeners.CardsListener;

import java.util.ArrayList;

import models.Card;
import models.CardHubDBHelper;


public class CardsFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, CardsListener {

    private ListView lvCards;
    private SearchView searchView;
    private SwipeRefreshLayout swipeRefreshLayout;
    private CardController cardController;

    public CardsFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_cards, container, false);
        setHasOptionsMenu(true);

        lvCards = view.findViewById(R.id.lvCards);

        lvCards.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Intent intent = new Intent(getContext(), CardDetailsActivity.class);
                intent.putExtra(CardDetailsActivity.CARD_ID, (int) l);
                startActivity(intent);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);

        cardController = new CardController(getContext());
        cardController.setCardsListener(this);
        cardController.fetchCards();

        return view;
    }

    @Override
    public void onCreateOptionsMenu(@NonNull Menu menu, @NonNull MenuInflater inflater) {
        inflater.inflate(R.menu.menu_search, menu);
        MenuItem itemSearch = menu.findItem(R.id.itemSearch);
        searchView = (SearchView) itemSearch.getActionView();

        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String s) { return false; }

            @Override
            public boolean onQueryTextChange(String s) {
                ArrayList<Card> tempCards = new ArrayList<>();
                CardHubDBHelper dbHelper = CardHubDBHelper.getInstance(getContext());

                for (Card card : dbHelper.getAllCards()) {
                    if (card.getName().toLowerCase().contains(s.toLowerCase())) {
                        tempCards.add(card);
                    }
                }

                lvCards.setAdapter(new CardAdapter(tempCards, getContext()));
                return true;
            }
        });

        super.onCreateOptionsMenu(menu, inflater);
    }
    

    @Override
    public void onRefresh() {
        cardController.fetchCards();
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshCardsList(ArrayList<Card> cardsList) {
        if(cardsList != null){
            lvCards.setAdapter(new CardAdapter(cardsList, getContext()));
        }
    }
}