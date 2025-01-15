package com.example.cardhub.controllers;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.example.cardhub.listeners.ProductsListener;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import models.Product;
import models.CardHubDBHelper;
import models.RestAPIClient;

public class ProductController {
    private final Context context;
    private ProductsListener productsListener;
    private ArrayList<Product> localProducts;

    private CardHubDBHelper cardHubDBHelper = null;

    public ProductController(Context context){
        this.context = context;
        cardHubDBHelper = CardHubDBHelper.getInstance(context);
        localProducts = new ArrayList<>();
    }

    public void setProductsListener(ProductsListener listener) {
        this.productsListener = listener;
    }

    public Product parseProduct(JSONObject productJson) throws JSONException {
        return new Product(
                productJson.getInt("id"),
                productJson.getInt("game_id"),
                productJson.getString("name"),
                productJson.getDouble("price"),
                productJson.getInt("stock"),
                productJson.getString("status"),
                productJson.getString("image_url"),
                productJson.getString("type"),
                productJson.isNull("description") || productJson.getString("description").isEmpty() ? null : productJson.getString("description"),
                productJson.getInt("created_at"),
                productJson.getInt("updated_at")
        );
    }

    /*
    Fetches all the products from the API

    This checks the internet connection, if the user has internet, it grabs the products from the API
    If the user doesn't have internet then it loads them from the local database
     */
    public void fetchProducts() {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();

            if (productsListener != null) {
                productsListener.onRefreshProductList(cardHubDBHelper.getAllProducts());
            }
            return;
        }

        RestAPIClient.getInstance(context).getRequest(Endpoints.PRODUCT_ENDPOINT, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    JSONArray productsArray = response.getJSONArray("object");
                    ArrayList<Product> productsList = new ArrayList<>();

                    cardHubDBHelper.removeAllProducts();

                    for (int i = 0; i < productsArray.length(); i++) {
                        JSONObject productJson = productsArray.getJSONObject(i);
                        Product product = parseProduct(productJson);
                        productsList.add(product);
                        cardHubDBHelper.insertProduct(product);
                    }

                    if (productsListener != null) {
                        productsListener.onRefreshProductList(productsList);
                    }
                } catch (JSONException e) {
                    Log.e("ProductController", "Error parsing product data", e);
                    Toast.makeText(context, e.toString(), Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onError(String error) {
                Log.e("ProductController", "Error fetching products: " + error);
                Toast.makeText(context, error, Toast.LENGTH_SHORT).show();
            }
        });
    }

    /*
    Fetches a single product

    This method tries to fetch a single product from the local database and sends it back as JSON
    If it isn't able to find the product in the database then
    It checks the internet connection, and if it exists, asks the API for the product
     */
    public void fetchSingleProduct(int productId, final RestAPIClient.APIResponseCallback callback) {
        Product localProduct = cardHubDBHelper.getProductById(productId);
        if (localProduct != null) {
            try {
                JSONObject localProductJson = new JSONObject();
                localProductJson.put("id", localProduct.getId());
                localProductJson.put("game_id", localProduct.getGameId());
                localProductJson.put("name", localProduct.getName());
                localProductJson.put("price", localProduct.getPrice());
                localProductJson.put("stock", localProduct.getStock());
                localProductJson.put("status", localProduct.getStatus());
                localProductJson.put("image_url", localProduct.getImageUrl());
                localProductJson.put("type", localProduct.getType());
                localProductJson.put("description", localProduct.getDescription());
                localProductJson.put("created_at", localProduct.getCreatedAt());
                localProductJson.put("updated_at", localProduct.getUpdatedAt());

                callback.onSuccess(localProductJson);
                return;
            } catch (JSONException e) {
                Log.e("ProductController", "Error converting local product to JSON", e);
            }
        }

        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, "No internet connection available.", Toast.LENGTH_SHORT).show();
            return;
        }

        String endpoint = Endpoints.PRODUCT_ENDPOINT + "/" + productId;
        RestAPIClient.getInstance(context).getRequestObject(endpoint, callback);
    }
}

