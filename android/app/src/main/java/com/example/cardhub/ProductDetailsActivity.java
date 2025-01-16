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
import com.example.cardhub.controllers.ProductController;

import org.json.JSONException;
import org.json.JSONObject;

import models.Product;
import models.RestAPIClient;

public class ProductDetailsActivity extends AppCompatActivity{

    public static final String PRODUCT_ID = "PRODUCT_ID";
    private Product product;
    private ProductController productController;

    private TextView tvProductName, tvProductPrice, tvProductDescription, tvProductStock;
    private ImageView productImage;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_product_details);

        // Toolbar
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        tvProductName = findViewById(R.id.tvProductName);
        tvProductPrice = findViewById(R.id.tvProductPrice);
        tvProductDescription = findViewById(R.id.tvProductDescription);
        tvProductStock = findViewById(R.id.tvProductStock);
        productImage = findViewById(R.id.productImage);

        productController = new ProductController(this);
        fetchProductDetails();
    }

    private void fetchProductDetails() {
        int productId = getIntent().getIntExtra(PRODUCT_ID, 0);

        productController.fetchSingleProduct(productId, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    Log.d("ProductDetailsActivity", "Response: " + response.toString());

                    product = productController.parseProduct(response);
                    Log.d("ProductDetailsActivity", "Parsed Product: " + product.toString());

                    if (product != null){
                        loadProduct();
                    } else {
                        Toast.makeText(ProductDetailsActivity.this, "Parsed product is null", Toast.LENGTH_SHORT).show();
                    }

                } catch (JSONException e) {
                    Log.e("ProductDetailsActivity", "JSON Parsing Error: " + e.getMessage(), e);
                    Toast.makeText(ProductDetailsActivity.this, "Error parsing product data", Toast.LENGTH_SHORT).show();
                } catch (Exception e) {
                    Log.e("ProductDetailsActivity", "Unexpected Error: " + e.getMessage(), e);
                    Toast.makeText(ProductDetailsActivity.this, "Unexpected error occurred", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onError(String error) {
                Toast.makeText(ProductDetailsActivity.this, "Error fetching product details", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void loadProduct() {
        if (product == null) {
            Log.e("ProductDetailsActivity", "Product is null");
            return;
        }

        // Setting Title
        setTitle("Details: " + product.getName());

        // Name
        String name = product.getName();
        SpannableString spannableName = new SpannableString("Product Name: " + name);
        spannableName.setSpan(new StyleSpan(Typeface.BOLD), 0, 13, 0);
        tvProductName.setText(spannableName);

        // Price
        String price = "Price: " + String.format("%.2f€", product.getPrice());
        SpannableString spannablePrice = new SpannableString("Price: " + price);
        spannablePrice.setSpan(new StyleSpan(Typeface.BOLD), 0, 6, 0);
        tvProductPrice.setText(spannablePrice);

        // Description
        String description = product.getDescription();
        if (description == null || description.isEmpty()) {
            description = "No description available";
        }
        SpannableString spannableDescription = new SpannableString("Description: \n" + description);
        spannableDescription.setSpan(new StyleSpan(Typeface.BOLD), 0, 12, 0);
        tvProductDescription.setText(spannableDescription);

        // Load product image using Glide
        String imageUrl = product.getImageUrl();
        if (imageUrl == null || imageUrl.isEmpty()) {
            Log.w("ProductDetailsActivity", "Image URL is null or empty");
            imageUrl = "";
        }
        Glide.with(this)
                .load(imageUrl)
                .placeholder(R.drawable.default_card)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .into(productImage);

        // Debug Log
        Log.d("ProductDetailsActivity", "Loaded Product: " + product.getName());
    }


    @Override
    public boolean onOptionsItemSelected(android.view.MenuItem item) {
        if (item.getItemId() == android.R.id.home) {
            onBackPressed();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}