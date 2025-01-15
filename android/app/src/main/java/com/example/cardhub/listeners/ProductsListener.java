package com.example.cardhub.listeners;

import java.util.ArrayList;

import models.Product;

public interface ProductsListener {
    void onRefreshProductList(ArrayList<Product> productList);
}
