package com.example.cardhub;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.cardhub.listeners.CardSingleListener;

public class CardDetailsActivity extends AppCompatActivity implements CardSingleListener {

    public static final String CARD_ID = "CARD_ID";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_card_details);


    }

    @Override
    public void onRefreshCard(int op) {

    }
}