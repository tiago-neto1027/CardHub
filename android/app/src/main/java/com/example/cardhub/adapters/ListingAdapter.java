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

import models.Listing;

public class ListingAdapter extends BaseAdapter {

    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<Listing> listingList;

    public ListingAdapter(ArrayList<Listing> listingList, Context context){
        this.listingList = listingList;
        this.context = context;
    }

    @Override
    public int getCount() {
        return listingList.size();
    }

    @Override
    public Object getItem(int i) {
        return listingList.get(i);
    }

    @Override
    public long getItemId(int i) {
        return listingList.get(i).getId();
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {

        if(layoutInflater == null){
            layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if(view == null){
            view = layoutInflater.inflate(R.layout.item_listing, null);
        }

        ListingViewHolder listingViewHolder = (ListingViewHolder) view.getTag();
        if(listingViewHolder == null){
            listingViewHolder = new ListingViewHolder(view);
            view.setTag(listingViewHolder);
        }

        listingViewHolder.update(listingList.get(i));
        return view;
    }

    private class ListingViewHolder{
        ImageView ivCardImage;
        TextView tvCardName, tvSellerUsername, tvListingPrice, tvListingCondition;

        public ListingViewHolder(View view){
            tvCardName = view.findViewById(R.id.tvListingCardName);
            tvSellerUsername = view.findViewById(R.id.tvListingSellerUsername);
            tvListingPrice = view.findViewById(R.id.tvListingPrice);
            tvListingCondition = view.findViewById(R.id.tvListingCondition);
            ivCardImage = view.findViewById(R.id.ivListingCardImage);
        }

        public void update(Listing listing){
            tvCardName.setText(listing.getCardName());
            tvSellerUsername.setText(listing.getSellerUsername());
            tvListingPrice.setText(String.format("%.2f€", listing.getPrice()));
            tvListingCondition.setText(listing.getCondition());

            Glide.with(context)
                    .load(listing.getCardImageUrl())
                    .placeholder(R.drawable.default_card)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(ivCardImage);
        }
    }
}
