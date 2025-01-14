package com.example.cardhub;

import android.graphics.Typeface;
import android.os.Bundle;
import android.text.SpannableString;
import android.text.style.StyleSpan;
import android.util.Log;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.example.cardhub.controllers.CardController;

import org.json.JSONException;
import org.json.JSONObject;

import models.Card;
import models.RestAPIClient;

public class CardDetailsActivity extends AppCompatActivity{

    //TODO: Create a button to show the listings for the card
    public static final String CARD_ID = "CARD_ID";
    private Card card;
    private CardController cardController;

    private TextView tvName, tvRarity, tvDescription;
    private ImageView cardImage;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_card_details);

        //Toolbar
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        tvName = findViewById(R.id.tvDetailCardName);
        tvRarity = findViewById(R.id.tvDetailCardRarity);
        tvDescription = findViewById(R.id.tvDetailCardDescription);
        cardImage = findViewById(R.id.cardImage);

        cardController = new CardController(this);
        fetchCardDetails();
    }

    public void fetchCardDetails(){
        int cardId = getIntent().getIntExtra(CARD_ID, 0);

        cardController.fetchSingleCard(cardId, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try{
                    card = cardController.parseCard(response);

                    if (card != null)
                        loadCard();

                } catch (JSONException e){
                    Toast.makeText(CardDetailsActivity.this, "Error parsing card data", Toast.LENGTH_SHORT).show();
                }
            }
            @Override
            public void onError(String error) {
                Log.d("RestAPIClient", "SingleCard onError: " + error);
                Toast.makeText(CardDetailsActivity.this, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void loadCard(){
        //TODO: Show the listing count for the card
        //Sets the Activity Title
        setTitle("Details: " + card.getName());

        //Card Name
        String nameText = "Card Name: " + card.getName();
        SpannableString spannableName = new SpannableString(nameText);
        spannableName.setSpan(new StyleSpan(Typeface.BOLD), 0, 11, 0);
        tvName.setText(spannableName);

        //Rarity
        String rarityText = "Rarity: " + card.getRarity();
        SpannableString spannableRarity = new SpannableString(rarityText);
        spannableRarity.setSpan(new StyleSpan(Typeface.BOLD), 0, 7, 0);
        tvRarity.setText(spannableRarity);

        //Description
        String descriptionText = "Description: \n" + card.getDescription();
        SpannableString spannableDescription = new SpannableString(descriptionText);
        spannableDescription.setSpan(new StyleSpan(Typeface.BOLD), 0, 12, 0);
        tvDescription.setText(spannableDescription);

        //Image
        Glide.with(getApplicationContext())
                .load(card.getImageUrl())
                .placeholder(R.drawable.default_card)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .into(cardImage);
    }

    //Toolbar to go back
    @Override
    public boolean onOptionsItemSelected(android.view.MenuItem item) {
        if (item.getItemId() == android.R.id.home) {
            onBackPressed();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}