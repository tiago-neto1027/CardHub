package com.example.cardhub;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.graphics.Insets;
import androidx.core.view.GravityCompat;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.fragment.app.Fragment;

import com.example.cardhub.adapters.CartAdapter;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import models.Card;
import models.CardHubDBHelper;
import models.CartItem;
import models.RestAPIClient;

public class CartActivity extends AppCompatActivity {
    private ListView lvCartItems;
    private CardHubDBHelper cardHubDBHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);

        //Top bar with title and buttons
        Toolbar toolbar = findViewById(R.id.toolbar);
        setTitle(null);
        TextView toolbarTitle = findViewById(R.id.toolbar_title);
        toolbarTitle.setText(R.string.cart);
        setSupportActionBar(toolbar);

        //Bottom bar with nav buttons
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.nav_shop);
        bottomNavigationView.setOnNavigationItemSelectedListener(this::onBottomNavigationItemSelected);

        //Inflate the page with items
        lvCartItems = findViewById(R.id.lvCartItems);
        cardHubDBHelper = CardHubDBHelper.getInstance(getApplicationContext());
        ArrayList<CartItem> cartItemList = cardHubDBHelper.getAllCartItems();
        CartAdapter cartAdapter = new CartAdapter(cartItemList, this);
        lvCartItems.setAdapter(cartAdapter);
    }

    public boolean onBottomNavigationItemSelected(@NonNull MenuItem item) {
        Intent intent = null;

        if (item.getItemId() == R.id.nav_home) {
            intent = new Intent(this, HomeActivity.class);
        } else if (item.getItemId() == R.id.nav_shop) {
            intent = new Intent(this, ShopActivity.class);
        } else if (item.getItemId() == R.id.nav_wishlist) {
            intent = new Intent(this, FavoriteActivity.class);
        } else if (item.getItemId() == R.id.nav_profile) {
            intent = new Intent(this, UserActivity.class);
        }
        if (intent != null) {
            startActivity(intent);
            finish();
            return true;
        }
        return false;
    }

    public void showPaymentMethodDialog(View view) {
        String[] paymentMethods = {"PayPal", "MbWay"};
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Select Payment Method")
                .setSingleChoiceItems(paymentMethods, -1, (dialog, which) -> {
                    String selectedPayment = paymentMethods[which];
                    dialog.dismiss();
                    proceedToCheckout(selectedPayment);
                })
                .setNegativeButton("Cancel", null)
                .show();
    }

    private void proceedToCheckout(String paymentMethod) {
        ArrayList<CartItem> cartItems = cardHubDBHelper.getAllCartItems();
        if (cartItems.isEmpty()) {
            Toast.makeText(this, "Your cart is empty.", Toast.LENGTH_SHORT).show();
            return;
        }

        if (!NetworkUtils.hasInternet(this)){
            Toast.makeText(this, "You don't have access to the internet.", Toast.LENGTH_SHORT).show();
            return;
        }

        //Building JSON
        try {
            JSONObject checkoutData = new JSONObject();
            checkoutData.put("payment_method", paymentMethod);

            JSONArray itemsArray = new JSONArray();
            for (CartItem item : cartItems) {
                JSONObject itemJson = new JSONObject();
                itemJson.put("itemId", item.getItemId());
                itemJson.put("type", "product");
                itemJson.put("quantity", item.getQuantity());
                itemsArray.put(itemJson);
            }
            checkoutData.put("items", itemsArray);

            //Sends to the server
            RestAPIClient.getInstance(this).postRequest(Endpoints.INVOICE_ENDPOINT, checkoutData, new RestAPIClient.APIResponseCallback() {
                @Override
                public void onSuccess(JSONObject response) {
                    try{
                        JSONObject data = response.getJSONObject("data");
                        String invoiceId = data.getString("invoice_id");
                        handlePaymentCompletion(invoiceId);
                        cardHubDBHelper.clearCart();
                    } catch (JSONException e) {
                        Toast.makeText(CartActivity.this, "Error processing response.", Toast.LENGTH_SHORT).show();
                    }
                }

                @Override
                public void onError(String error) {
                    Toast.makeText(CartActivity.this, "Error: " + error, Toast.LENGTH_LONG).show();
                }
            });
        } catch (Exception e) {
            Toast.makeText(this, "Failed to create checkout request.", Toast.LENGTH_SHORT).show();
        }
    }

    private void handlePaymentCompletion(String invoiceId) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Complete Payment")
                .setMessage("Simulate the payment process completion?")
                .setPositiveButton("Complete Payment", (dialog, which) -> {
                    sendPaymentStatus(invoiceId, "completed");
                })
                .setNegativeButton("Cancel", null)
                .show();
    }

    private void sendPaymentStatus(String invoiceId, String status) {
        try {
            JSONObject statusData = new JSONObject();
            statusData.put("status", status);

            RestAPIClient.getInstance(this).postRequest(Endpoints.INVOICE_ENDPOINT+ "/" + invoiceId + "/status",
                    statusData, new RestAPIClient.APIResponseCallback() {
                        @Override
                        public void onSuccess(JSONObject response) {
                            String invoiceUrl = "http://13.39.156.210/invoice/view?id=" + invoiceId;
                            Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(invoiceUrl));
                            startActivity(browserIntent);
                            finish();
                        }

                        @Override
                        public void onError(String error) {
                            Toast.makeText(CartActivity.this, "Error updating payment status: " + error, Toast.LENGTH_LONG).show();
                        }
                    });

        } catch (Exception e) {
            Toast.makeText(this, "Failed to send payment status.", Toast.LENGTH_SHORT).show();
        }
    }
}