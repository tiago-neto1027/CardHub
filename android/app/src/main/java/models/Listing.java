package models;

public class Listing {
    private int id;
    private int sellerId;
    private String sellerUsername;
    private int cardId;
    private String cardName;
    private String cardImageUrl;
    private double price;
    private String condition;
    private String status;
    private int createdAt;
    private int updatedAt;

    public Listing(int id, int sellerId, String sellerUsername, int cardId, String cardName, String cardImageUrl, double price, String condition, String status, int createdAt, int updatedAt) {
        this.id = id;
        this.sellerId = sellerId;
        this.sellerUsername = sellerUsername;
        this.cardId = cardId;
        this.cardName = cardName;
        this.cardImageUrl = cardImageUrl;
        this.price = price;
        this.condition = condition;
        this.status = status;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getSellerId() { return sellerId; }
    public void setSellerId(int sellerId) { this.sellerId = sellerId; }

    public String getSellerUsername() { return sellerUsername; }
    public void setSellerUsername(String sellerUsername) { this.sellerUsername = sellerUsername; }

    public int getCardId() { return cardId; }
    public void setCardId(int cardId) { this.cardId = cardId; }

    public String getCardName() { return cardName; }
    public void setCardName(String cardName) { this.cardName = cardName; }

    public String getCardImageUrl() { return cardImageUrl; }
    public void setCardImageUrl(String cardImageUrl) { this.cardImageUrl = cardImageUrl; }

    public double getPrice() { return price; }
    public void setPrice(double price) { this.price = price; }

    public String getCondition() { return condition; }
    public void setCondition(String condition) { this.condition = condition; }

    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }

    public int getCreatedAt() { return createdAt; }
    public void setCreatedAt(int createdAt) { this.createdAt = createdAt; }

    public int getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(int updatedAt) { this.updatedAt = updatedAt; }
}