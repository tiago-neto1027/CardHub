package com.example.cardhub.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.example.cardhub.R;

import java.util.ArrayList;

import models.Product;

public class ProductAdapter extends BaseAdapter {

    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<Product> productList;

    public ProductAdapter(ArrayList<Product> productList, Context context){
        this.productList = productList;
        this.context = context;
    }

    @Override
    public int getCount() { return productList.size(); }

    @Override
    public Object getItem(int i) { return productList.get(i); }

    @Override
    public long getItemId(int i) { return productList.get(i).getId(); }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (layoutInflater == null) {
            layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.item_product, null);
        }

        ProductViewHolder productViewHolder = (ProductViewHolder) convertView.getTag();
        if (productViewHolder == null) {
            productViewHolder = new ProductViewHolder(convertView);
            convertView.setTag(productViewHolder);
        }

        productViewHolder.update(productList.get(position));
        return convertView;
    }

    private class ProductViewHolder {
        ImageView ivProductImage;
        TextView tvProductName, tvProductPrice, tvProductStock ;

        public ProductViewHolder(View view) {
            ivProductImage = view.findViewById(R.id.ivProductImage);
            tvProductName = view.findViewById(R.id.tvProductName);
            tvProductPrice = view.findViewById(R.id.tvProductPrice);
            tvProductStock = view.findViewById(R.id.tvProductStock);
        }

        public void update(Product product) {
            tvProductName.setText(product.getName());
            tvProductPrice.setText(String.format("%.2f€", product.getPrice()));
            tvProductStock.setText(String.valueOf(product.getStock()));

            Glide.with(context)
                    .load(product.getImageUrl())
                    .placeholder(R.drawable.default_card)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(ivProductImage);
        }
    }
}
