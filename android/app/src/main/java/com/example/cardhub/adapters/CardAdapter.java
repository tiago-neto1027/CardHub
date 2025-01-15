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

public class CardAdapter extends BaseAdapter {

    private Context context;
    private LayoutInflater layoutInflater;
    private ArrayList<Card> cardList;

    public CardAdapter(ArrayList<Card> cardList, Context context){
        this.cardList = cardList;
        this.context = context;
    }

    @Override
    public int getCount() { return cardList.size(); }

    @Override
    public Object getItem(int i) { return cardList.get(i); }

    @Override
    public long getItemId(int i) { return cardList.get(i).getId(); }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {

        if(layoutInflater == null){
            layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if(view == null){
            view = layoutInflater.inflate(R.layout.item_card, null);
        }

        CardViewHolder cardViewHolder = (CardViewHolder) view.getTag();
        if(cardViewHolder == null){
            cardViewHolder = new CardViewHolder(view);
            view.setTag(cardViewHolder);
        }

        cardViewHolder.update(cardList.get(i));
        return view;
    }

    private class CardViewHolder{
        ImageView ivCardImage;
        TextView tvCardName, tvCardRarity;

        public CardViewHolder(View view){
            tvCardName = view.findViewById(R.id.tvCardName);
            tvCardRarity = view.findViewById(R.id.tvCardRarity);
            ivCardImage = view.findViewById(R.id.ivCardImage);
        }

        public void update(Card card){
            tvCardName.setText(card.getName());
            tvCardRarity.setText(card.getRarity());

            Glide.with(context)
                    .load(card.getImageUrl())
                    .placeholder(R.drawable.default_card)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(ivCardImage);
        }
    }
}
