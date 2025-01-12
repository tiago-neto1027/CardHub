package models;

public class Card {
    private int id;
    private int gameId;
    private String name;
    private String rarity;
    private String imageUrl;
    private String status;
    private String description;
    private int createdAt;
    private int updatedAt;
    private Integer userId;

    public Card(int id, int gameId, String name, String rarity, String imageUrl, String status, String description, int createdAt, int updatedAt, Integer userId) {
        this.id = id;
        this.gameId = gameId;
        this.name = name;
        this.rarity = rarity;
        this.imageUrl = imageUrl;
        this.status = status;
        this.description = description;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
        this.userId = userId;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public int getGameId() { return gameId; }
    public void setGameId(int gameId) { this.gameId = gameId; }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public String getRarity() { return rarity; }
    public void setRarity(String rarity) { this.rarity = rarity; }

    public String getImageUrl() { return imageUrl; }
    public void setImageUrl(String imageUrl) { this.imageUrl = imageUrl; }

    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public int getCreatedAt() { return createdAt; }
    public void setCreatedAt(int createdAt) { this.createdAt = createdAt; }

    public int getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(int updatedAt) { this.updatedAt = updatedAt; }

    public int getUserId() { return userId; }
    public void setUserId(Integer userId) { this.userId = userId; }
}
