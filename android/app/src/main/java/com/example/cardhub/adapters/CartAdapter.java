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

import models.Card;
import models.CartItem;

public class CartAdapter extends BaseAdapter {

    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<CartItem> cartList;

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
        TextView tvCartID, tvQuantity;

        public CartViewHolder(View view){
            ivCardImage = view.findViewById(R.id.ivCardImage);
            tvQuantity = view.findViewById(R.id.tvquantity);
            tvCartID = view.findViewById(R.id.tvCartID);
        }

        public void update(CartItem cartItem){
            tvQuantity.setText(String.valueOf(cartItem.getQuantity()));
            tvCartID.setText(String.valueOf(cartItem.getId()));

            Glide.with(context)
                    .load("")
                    .placeholder(R.drawable.default_card)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(ivCardImage);
        }
    }
}
