package com.example.cardhub;

import android.os.Bundle;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.cardhub.adapters.CardAdapter;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import models.RestAPIClient;

public class CardActivity extends AppCompatActivity {

    private RecyclerView rvCards;
    private CardAdapter cardAdapter;
    private List<CardAdapter.Card> cardList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cards);

        rvCards = findViewById(R.id.rvCards);
        rvCards.setLayoutManager(new LinearLayoutManager(this));
        cardAdapter = new CardAdapter(cardList);
        rvCards.setAdapter(cardAdapter);

        fetchCards();
    }

    private void fetchCards() {
        RestAPIClient.getInstance(this).getCards(new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    JSONArray cardsArray = response.getJSONArray("cards");
                    for (int i = 0; i < cardsArray.length(); i++) {
                        JSONObject cardJson = cardsArray.getJSONObject(i);

                        CardAdapter.Card card = new CardAdapter.Card();
                        card.id = cardJson.getInt("id");
                        card.gameId = cardJson.getInt("gameId");
                        card.name = cardJson.getString("name");
                        card.rarity = cardJson.getString("rarity");
                        card.imageUrl = cardJson.getString("imageUrl");
                        card.status = cardJson.getString("status");
                        card.description = cardJson.getString("description");
                        card.createdAt = cardJson.getInt("createdAt");
                        card.updatedAt = cardJson.getInt("updatedAt");
                        card.userId = cardJson.getInt("userId");

                        cardList.add(card);
                    }

                    cardAdapter.notifyDataSetChanged();
                } catch (JSONException e) {
                    Toast.makeText(CardActivity.this, "Error parsing cards data", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onError(String error) {
                Toast.makeText(CardActivity.this, "The endpoint might be wrong.", Toast.LENGTH_SHORT).show();
            }
        });
    }
}