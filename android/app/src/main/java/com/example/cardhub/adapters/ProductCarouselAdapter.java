package com.example.cardhub.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.cardhub.R;

import java.util.ArrayList;
import java.util.List;

import models.Product;

public class ProductCarouselAdapter extends RecyclerView.Adapter<ProductCarouselAdapter.ProductViewHolder> {

    private final List<Product> productList;

    public ProductCarouselAdapter(ArrayList<Product> productList) {
        this.productList = productList;
    }

    @NonNull
    @Override
    public ProductViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_product_carousel, parent, false);
        return new ProductViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ProductViewHolder holder, int position) {
        Product product = productList.get(position);

        holder.nameTextView.setText(product.getName());
        holder.priceTextView.setText(String.format("%.2f€", product.getPrice()));

        // Load image using Glide
        Glide.with(holder.imageView.getContext())
                .load(product.getImageUrl())
                .into(holder.imageView);
    }

    @Override
    public int getItemCount() {
        return productList.size();
    }

    public static class ProductViewHolder extends RecyclerView.ViewHolder {
        ImageView imageView;
        TextView nameTextView;
        TextView priceTextView;

        public ProductViewHolder(View itemView) {
            super(itemView);
            imageView = itemView.findViewById(R.id.product_image);
            nameTextView = itemView.findViewById(R.id.product_name);
            priceTextView = itemView.findViewById(R.id.product_price);
        }
    }
}
