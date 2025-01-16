package models;

public class Favorite {
    private int cardId;

    public Favorite(int cardId) {
        this.cardId = cardId;
    }

    public int getCardId() {
        return cardId;
    }
    public void setCardId(int cardId) {
        this.cardId = cardId;
    }
}
