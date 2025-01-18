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
import com.example.cardhub.controllers.ListingController;

import java.util.ArrayList;

import models.Card;
import models.CardHubDBHelper;
import models.CartItem;
import models.Listing;
import models.Product;

public class CartAdapter extends BaseAdapter {

    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<CartItem> cartList;
    private CardHubDBHelper cardHubDBHelper;

    public CartAdapter(ArrayList<CartItem> cartList, Context context){
        this.cartList = cartList;
        this.context = context;
    }

    @Override
    public int getCount() { return cartList.size(); }

    @Override
    public Object getItem(int i) { return cartList.get(i); }

    @Override
    public long getItemId(int i) { return cartList.get(i).getId(); }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {

        if(layoutInflater == null){
            layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if(view == null){
            view = layoutInflater.inflate(R.layout.item_cart, null);
        }

        CartViewHolder cartViewHolder = (CartViewHolder) view.getTag();
        if(cartViewHolder == null){
            cartViewHolder = new CartViewHolder(view);
            view.setTag(cartViewHolder);
        }

        cartViewHolder.update(cartList.get(i));
        return view;
    }

    private class CartViewHolder {
        ImageView ivCardImage;
        TextView tvName, tvPrice, tvQuantity;
        View btnIncrease, btnDecrease;

        public CartViewHolder(View view){
            ivCardImage = view.findViewById(R.id.ivCardImage);
            tvName = view.findViewById(R.id.tvName);
            tvPrice = view.findViewById(R.id.tvPrice);
            tvQuantity = view.findViewById(R.id.tvQuantity);
            btnIncrease = view.findViewById(R.id.btnIncreaseQuantity);
            btnDecrease = view.findViewById(R.id.btnDecreaseQuantity);
        }

        public void update(CartItem cartItem){
            cardHubDBHelper = CardHubDBHelper.getInstance(context);
            String image = null;
            if(cartItem.getType().equals("listing")){
                Listing listing = cardHubDBHelper.getListingById(cartItem.getItemId());
                tvName.setText(String.valueOf(listing.getCardName()));
                tvPrice.setText(String.format("%.2f€", listing.getPrice()));
                image = listing.getCardImageUrl();
                tvQuantity.setText("1");

                //Disable buttons for listing
                btnIncrease.setVisibility(View.GONE);
                btnDecrease.setVisibility(View.GONE);
            }
            if(cartItem.getType().equals("product")){
                Product product = cardHubDBHelper.getProductById(cartItem.getItemId());
                tvName.setText(String.valueOf(product.getName()));
                tvPrice.setText(String.format("%.2f€", product.getPrice()*cartItem.getQuantity()));
                tvQuantity.setText(String.valueOf(cartItem.getQuantity()));
                image = product.getImageUrl();

                btnIncrease.setVisibility(View.VISIBLE);
                btnDecrease.setVisibility(View.VISIBLE);

                btnIncrease.setOnClickListener(v -> {
                    if (cartItem.getQuantity() < product.getStock()) {
                        cartItem.setQuantity(cartItem.getQuantity() + 1);
                        tvQuantity.setText(String.valueOf(cartItem.getQuantity()));
                        tvPrice.setText(String.format("%.2f€", product.getPrice() * cartItem.getQuantity()));
                        updateDatabase(cartItem);
                    }
                });

                btnDecrease.setOnClickListener(v -> {
                    if (cartItem.getQuantity() > 1) {
                        cartItem.setQuantity(cartItem.getQuantity() - 1);
                        tvQuantity.setText(String.valueOf(cartItem.getQuantity()));
                        tvPrice.setText(String.format("%.2f€", product.getPrice() * cartItem.getQuantity()));
                        updateDatabase(cartItem);
                    }
                });
            }

            Glide.with(context)
                    .load(image)
                    .placeholder(R.drawable.default_card)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(ivCardImage);
        }
    }

    private void updateDatabase(CartItem cartItem) {
        cardHubDBHelper.updateCartItemQuantity(cartItem.getId(), cartItem.getQuantity());
    }
}
