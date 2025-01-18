package models;

public class CartItem {
    private int id;
    private int itemId;
    private String type;
    private int quantity;

    public CartItem(int id, int itemId, String type, int quantity) {
        this.id = id;
        this.itemId = itemId;
        this.type = type;
        this.quantity = quantity;
    }

    public CartItem(int itemId, String type, int quantity) {
        this.itemId = itemId;
        this.type = type;
        this.quantity = quantity;
    }

    public int getId() { return id; }
    public int getItemId() { return itemId; }
    public String getType() { return type; }
    public int getQuantity() { return quantity; }
    public void setQuantity(int quantity) { this.quantity = quantity; }

    @Override
    public String toString() {
        return "CartItem{id=" + id + ", itemId=" + itemId + ", type='" + type + "', quantity=" + quantity + '}';
    }
}