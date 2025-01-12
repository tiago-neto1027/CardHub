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

import java.util.List;

public class CardAdapter extends RecyclerView.Adapter<CardAdapter.CardViewHolder> {
    private List<Card> cardList;

    public static class Card {
        public int id;
        public int gameId;
        public String name;
        public String rarity;
        public String imageUrl;
        public String status;
        public String description;
        public int createdAt;
        public int updatedAt;
        public int userId;
    }

    public CardAdapter(List<Card> cardList) {
        this.cardList = cardList;
    }

    @NonNull
    @Override
    public CardViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_card, parent, false);
        return new CardViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull CardViewHolder holder, int position) {
        Card card = cardList.get(position);
        holder.tvCardName.setText(card.name);
        holder.tvCardRarity.setText(card.rarity);
        holder.tvCardDescription.setText(card.description != null ? card.description : "No Description Available");
        Glide.with(holder.itemView.getContext())
                .load(card.imageUrl)
                .placeholder(R.drawable.default_card)
                .into(holder.ivCardImage);
    }

    @Override
    public int getItemCount() {
        return cardList.size();
    }

    public static class CardViewHolder extends RecyclerView.ViewHolder {
        ImageView ivCardImage;
        TextView tvCardName, tvCardRarity, tvCardDescription;

        public CardViewHolder(@NonNull View itemView) {
            super(itemView);
            tvCardName = itemView.findViewById(R.id.tvCardName);
            tvCardRarity = itemView.findViewById(R.id.tvCardRarity);
            tvCardDescription = itemView.findViewById(R.id.tvCardDescription);
            ivCardImage = itemView.findViewById(R.id.ivCardImage);
        }
    }
}
