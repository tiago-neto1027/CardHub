package models;

public class Product {
    private int id;
    private int gameId;
    private String name;
    private double price;
    private int stock;
    private String status;
    private String imageUrl;
    private String type;
    private String description;
    private int createdAt;
    private int updatedAt;

    public Product(int id, int gameId, String name, double price, int stock, String status, String imageUrl, String type, String description, int createdAt, int updatedAt) {
        this.id = id;
        this.gameId = gameId;
        this.name = name;
        this.price = price;
        this.stock = stock;
        this.status = status;
        this.imageUrl = imageUrl;
        this.type = type;
        this.description = description;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getGameId() { return gameId; }
    public void setGameId(int gameId) { this.gameId = gameId; }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public double getPrice() { return price; }
    public void setPrice(double price) { this.price = price; }

    public int getStock() { return stock; }
    public void setStock(int stock) { this.stock = stock; }

    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }

    public String getImageUrl() { return imageUrl; }
    public void setImageUrl(String imageUrl) { this.imageUrl = imageUrl; }

    public String getType() { return type; }
    public void setType(String type) { this.type = type; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public int getCreatedAt() { return createdAt; }
    public void setCreatedAt(int createdAt) { this.createdAt = createdAt; }

    public int getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(int updatedAt) { this.updatedAt = updatedAt; }
}
