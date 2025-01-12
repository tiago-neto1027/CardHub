package com.example.cardhub.listeners;

import java.util.ArrayList;

import models.Card;

public interface CardsListener {
    void onRefreshCardsList(ArrayList<Card> cardsList);
}
