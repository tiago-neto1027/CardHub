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
import com.example.cardhub.adapters.ProductAdapter;
import com.example.cardhub.controllers.ProductController;
import com.example.cardhub.listeners.ProductsListener;

import java.util.ArrayList;

import models.Product;
import models.CardHubDBHelper;
import models.RestAPIClient;

public class ProductsFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener, ProductsListener {

    private ListView lvProducts;
    private SearchView searchView;
    private SwipeRefreshLayout swipeRefreshLayout;
    private ProductController productController;

    public ProductsFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_products, container, false);
        setHasOptionsMenu(true);

        lvProducts = view.findViewById(R.id.lvProducts);

        lvProducts.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Intent intent = new Intent(getContext(), ProductDetailsActivity.class);
                intent.putExtra(ProductDetailsActivity.PRODUCT_ID, (int) l);
                startActivity(intent);
            }
        });

        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);

        productController = new ProductController(getContext());
        productController.setProductsListener(this);
        productController.fetchProducts();

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
                ArrayList<Product> tempProducts = new ArrayList<>();

                try (CardHubDBHelper dbHelper = new CardHubDBHelper(getContext())) {
                    for (Product product : dbHelper.getAllProducts()) {
                        if (product.getName().toLowerCase().contains(s.toLowerCase())) {
                            tempProducts.add(product);
                        }
                    }
                }

                lvProducts.setAdapter(new ProductAdapter(tempProducts, getContext()));
                return true;
            }
        });

        super.onCreateOptionsMenu(menu, inflater);
    }

    @Override
    public void onRefresh() {
        productController.fetchProducts();
        swipeRefreshLayout.setRefreshing(false);
    }

    @Override
    public void onRefreshProductList(ArrayList<Product> productList) {
        if(productList != null){
            lvProducts.setAdapter(new ProductAdapter(productList, getContext()));
        }
    }
}
