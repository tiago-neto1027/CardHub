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
import com.example.cardhub.controllers.ListingController;

import org.json.JSONException;
import org.json.JSONObject;

import models.Listing;
import models.RestAPIClient;

public class ListingDetailsActivity extends AppCompatActivity {

    //TODO: Create a button to add the listing to the cart
    public static final String LISTING_ID = "LISTING_ID";
    private Listing listing;
    private ListingController listingController;

    private TextView tvCardName, tvSellerUsername, tvPrice, tvCondition;
    private ImageView cardImage;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_listing_details);

        //Toolbar
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        tvCardName = findViewById(R.id.tvDetailListingCardName);
        tvSellerUsername = findViewById(R.id.tvDetailSellerUser);
        tvPrice = findViewById(R.id.tvDetailPrice);
        tvCondition = findViewById(R.id.tvDetailCondition);
        cardImage = findViewById(R.id.listingCardImage);

        listingController = new ListingController(this);
        fetchListingDetails();
    }

    public void fetchListingDetails(){
        int listingId = getIntent().getIntExtra(LISTING_ID, 0);

        listingController.fetchSingleListing(listingId, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try{
                    listing = listingController.parseListing(response);

                    if(listing != null){
                        loadListing();
                    }

                } catch (JSONException e){
                    Toast.makeText(ListingDetailsActivity.this, "Error parsing listing data", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onError(String error) {
                Log.d("RestAPIClient", "SingleCard onError: " + error);
                Toast.makeText(ListingDetailsActivity.this, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void loadListing(){
        //Sets the Activity Title
        setTitle("Details: " + listing.getCardName() + " " + listing.getCondition());

        //Card Name
        String cardNameText = "Card Name: " + listing.getCardName();
        SpannableString spannableCardName = new SpannableString(cardNameText);
        spannableCardName.setSpan(new StyleSpan(Typeface.BOLD), 0, 11, 0);
        tvCardName.setText(spannableCardName);

        //Seller Username
        String sellerUsernameText = "Seller: " + listing.getSellerUsername();
        SpannableString spannableSeller = new SpannableString(sellerUsernameText);
        spannableSeller.setSpan(new StyleSpan(Typeface.BOLD), 0, 7, 0);
        tvSellerUsername.setText(spannableSeller);

        //Price
        String priceText = "Price: " + String.format("%.2f€", listing.getPrice());
        SpannableString spannablePrice = new SpannableString(priceText);
        spannablePrice.setSpan(new StyleSpan(Typeface.BOLD), 0, 6, 0);
        tvPrice.setText(spannablePrice);

        //Condition
        String conditionText = "Condition: " + listing.getCondition();
        SpannableString spannableCondition = new SpannableString(conditionText);
        spannableCondition.setSpan(new StyleSpan(Typeface.BOLD), 0, 10, 0);
        tvCondition.setText(spannableCondition);

        //Image
        Glide.with(getApplicationContext())
                .load(listing.getCardImageUrl())
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