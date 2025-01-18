package models;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;
import android.widget.Toast;

import androidx.annotation.Nullable;

import java.util.ArrayList;
import java.util.List;

public class CardHubDBHelper extends SQLiteOpenHelper {
    private static CardHubDBHelper instance;
    private static final String DATABASE_NAME = "cardhub.db";
    private static final int DATABASE_VERSION = 4;

    //Table Cards
    private static final String TABLE_CARDS = "cards";
    private static final String ID = "id";
    private static final String GAME_ID = "game_id";
    private static final String NAME = "name";
    private static final String RARITY = "rarity";
    private static final String IMAGE_URL = "image_url";
    private static final String STATUS = "status";
    private static final String DESCRIPTION = "description";
    private static final String CREATED_AT = "created_at";
    private static final String UPDATED_AT = "updated_at";
    private static final String USER_ID = "user_id";
    private static final String COUNT_LISTINGS = "count_listings";

    //Table Products
    private static final String TABLE_PRODUCTS = "products";
    private static final String PRODUCT_ID = "id";
    private static final String PRODUCT_GAME_ID = "game_id";
    private static final String PRODUCT_NAME = "name";
    private static final String PRODUCT_PRICE = "price";
    private static final String PRODUCT_STOCK = "stock";
    private static final String PRODUCT_STATUS = "status";
    private static final String PRODUCT_IMAGE_URL = "image_url";
    private static final String PRODUCT_TYPE = "type";
    private static final String PRODUCT_DESCRIPTION = "description";
    private static final String PRODUCT_CREATED_AT = "created_at";
    private static final String PRODUCT_UPDATED_AT = "updated_at";

    //Table Listings
    private static final String TABLE_LISTINGS = "listings";
    private static final String SELLER_ID = "seller_id";
    private static final String SELLER_USERNAME = "seller_username";
    private static final String CARD_ID = "card_id";
    private static final String CARD_NAME = "card_name";
    private static final String CARD_IMAGE_URL = "card_image_url";
    private static final String PRICE = "price";
    private static final String CONDITION = "condition";

    //Table Favorites
    private static final String TABLE_FAVORITES = "favorites";
    private static final String FAVORITE_CARD_ID = "card_id";

    //Table Cartitems
    private static final String TABLE_CARTITEMS = "cartitems";
    private static final String CART_ITEM_ID = "cart_item_id";
    private static final String ITEM_ID = "item_id";
    private static final String TYPE = "type";
    private static final String QUANTITY = "quantity";

    public CardHubDBHelper(@Nullable Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    public static synchronized CardHubDBHelper getInstance(Context context) {
        if (instance == null) {
            instance = new CardHubDBHelper(context.getApplicationContext());
        }
        return instance;
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        //Cards
        String createTableCards = "CREATE TABLE " + TABLE_CARDS + " (" +
                ID + " INTEGER PRIMARY KEY, " +
                GAME_ID + " INTEGER, " +
                NAME + " TEXT, " +
                RARITY + " TEXT, " +
                IMAGE_URL + " TEXT, " +
                STATUS + " TEXT, " +
                DESCRIPTION + " TEXT, " +
                CREATED_AT + " INTEGER, " +
                UPDATED_AT + " INTEGER, " +
                COUNT_LISTINGS + " INTEGER, " +
                USER_ID + " INTEGER)";
      sqLiteDatabase.execSQL(createTableCards);

        //Products
        String createTableProducts = "CREATE TABLE " + TABLE_PRODUCTS + " (" +
                PRODUCT_ID + " INTEGER PRIMARY KEY, " +
                PRODUCT_GAME_ID + " INTEGER, " +
                PRODUCT_NAME + " TEXT, " +
                PRODUCT_PRICE + " REAL, " +
                PRODUCT_STOCK + " INTEGER, " +
                PRODUCT_STATUS + " TEXT, " +
                PRODUCT_IMAGE_URL + " TEXT, " +
                PRODUCT_TYPE + " TEXT, " +
                PRODUCT_DESCRIPTION + " TEXT, " +
                PRODUCT_CREATED_AT + " INTEGER, " +
                PRODUCT_UPDATED_AT + " INTEGER)";
        sqLiteDatabase.execSQL(createTableProducts);

        
        //Listings
        String createTableListings = "CREATE TABLE " + TABLE_LISTINGS + " (" +
                ID + " INTEGER PRIMARY KEY, " +
                SELLER_ID + " INTEGER, " +
                SELLER_USERNAME + " TEXT, " +
                CARD_ID + " INTEGER, " +
                CARD_NAME + " TEXT, " +
                CARD_IMAGE_URL + " TEXT, " +
                PRICE + " REAL, " +
                CONDITION + " TEXT, " +
                STATUS + " TEXT, " +
                CREATED_AT + " INTEGER, " +
                UPDATED_AT + " INTEGER)";
        sqLiteDatabase.execSQL(createTableListings);

        String createTableFavorites = "CREATE TABLE " + TABLE_FAVORITES + " (" +
                FAVORITE_CARD_ID + " INTEGER PRIMARY KEY)";
        sqLiteDatabase.execSQL(createTableFavorites);

        String createTableCartitems = "CREATE TABLE " + TABLE_CARTITEMS + " (" +
                CART_ITEM_ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                ITEM_ID + " INTEGER, " +
                TYPE + " TEXT, " +
                QUANTITY + " INTEGER)";
        sqLiteDatabase.execSQL(createTableCartitems);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_CARDS);
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_PRODUCTS);
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_LISTINGS);
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_FAVORITES);
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_CARTITEMS);
        onCreate(sqLiteDatabase);
    }

    //region Cards
    public void insertCard(Card card) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(ID, card.getId());
            values.put(GAME_ID, card.getGameId());
            values.put(NAME, card.getName());
            values.put(RARITY, card.getRarity());
            values.put(IMAGE_URL, card.getImageUrl());
            values.put(STATUS, card.getStatus());
            values.put(DESCRIPTION, card.getDescription());
            values.put(CREATED_AT, card.getCreatedAt());
            values.put(UPDATED_AT, card.getUpdatedAt());
            values.put(USER_ID, card.getUserId());

            if (card.getCountListings() != null) {
                values.put(COUNT_LISTINGS, card.getCountListings());
            }

            db.insert(TABLE_CARDS, null, values);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error inserting card: " + e.getMessage());
        }
    }

    public ArrayList<Card> getAllCards() {
        ArrayList<Card> cardsList = new ArrayList<>();

        try (SQLiteDatabase db = getReadableDatabase(); Cursor cursor = db.rawQuery("SELECT * FROM " + TABLE_CARDS, null)) {
            if (cursor.moveToFirst()) {
                do {
                    Card card = new Card(
                            cursor.getInt(cursor.getColumnIndexOrThrow(ID)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(GAME_ID)),
                            cursor.getString(cursor.getColumnIndexOrThrow(NAME)),
                            cursor.getString(cursor.getColumnIndexOrThrow(RARITY)),
                            cursor.getString(cursor.getColumnIndexOrThrow(IMAGE_URL)),
                            cursor.getString(cursor.getColumnIndexOrThrow(STATUS)),
                            cursor.isNull(cursor.getColumnIndexOrThrow(DESCRIPTION)) ? null : cursor.getString(cursor.getColumnIndexOrThrow(DESCRIPTION)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(CREATED_AT)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(UPDATED_AT)),
                            cursor.isNull(cursor.getColumnIndexOrThrow(USER_ID)) ? null : cursor.getInt(cursor.getColumnIndexOrThrow(USER_ID))
                    );

                    Integer countListings = cursor.isNull(cursor.getColumnIndexOrThrow(COUNT_LISTINGS)) ? null : cursor.getInt(cursor.getColumnIndexOrThrow(COUNT_LISTINGS));
                    card.setCountListings(countListings);

                    cardsList.add(card);
                } while (cursor.moveToNext());
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error fetching cards: " + e.getMessage());
        }
        return cardsList;
    }

    public Card getCardById(int cardId) {

        try (SQLiteDatabase db = getReadableDatabase(); Cursor cursor = db.query(TABLE_CARDS, null, ID + "=?", new String[]{String.valueOf(cardId)}, null, null, null)) {
            if (cursor != null && cursor.moveToFirst()) {
                Card card = new Card(
                        cursor.getInt(cursor.getColumnIndexOrThrow(ID)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(GAME_ID)),
                        cursor.getString(cursor.getColumnIndexOrThrow(NAME)),
                        cursor.getString(cursor.getColumnIndexOrThrow(RARITY)),
                        cursor.getString(cursor.getColumnIndexOrThrow(IMAGE_URL)),
                        cursor.getString(cursor.getColumnIndexOrThrow(STATUS)),
                        cursor.isNull(cursor.getColumnIndexOrThrow(DESCRIPTION)) ? null : cursor.getString(cursor.getColumnIndexOrThrow(DESCRIPTION)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(CREATED_AT)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(UPDATED_AT)),
                        cursor.isNull(cursor.getColumnIndexOrThrow(USER_ID)) ? null : cursor.getInt(cursor.getColumnIndexOrThrow(USER_ID))
                );

                Integer countListings = cursor.isNull(cursor.getColumnIndexOrThrow(COUNT_LISTINGS)) ? null : cursor.getInt(cursor.getColumnIndexOrThrow(COUNT_LISTINGS));
                card.setCountListings(countListings);

                return card;
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error fetching card by ID: " + e.getMessage());
        }
        return null;
    }

    public boolean updateCard(Card card) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(ID, card.getId());
            values.put(GAME_ID, card.getGameId());
            values.put(NAME, card.getName());
            values.put(RARITY, card.getRarity());
            values.put(IMAGE_URL, card.getImageUrl());
            values.put(STATUS, card.getStatus());
            values.put(DESCRIPTION, card.getDescription());
            values.put(CREATED_AT, card.getCreatedAt());
            values.put(UPDATED_AT, card.getUpdatedAt());
            values.put(USER_ID, card.getUserId());

            if (card.getCountListings() != null) {
                values.put(COUNT_LISTINGS, card.getCountListings());
            }

            return db.update(TABLE_CARDS, values, ID + "=?", new String[]{String.valueOf(card.getId())}) > 0;
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error updating card: " + e.getMessage());
            return false;
        }
    }

    public void removeAllCards() {
        try (SQLiteDatabase db = getWritableDatabase()) {
            db.delete(TABLE_CARDS, null, null);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error removing all cards: " + e.getMessage());
        }
    }

    public boolean removeCardById(int cardId) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            return db.delete(TABLE_CARDS, ID + " = ?", new String[]{String.valueOf(cardId)}) == 1;
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error deleting card: " + e.getMessage());
            return false;
        }
    }
    //endregion

    //region Products
    public void insertProduct(Product product) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(PRODUCT_ID, product.getId());
            values.put(PRODUCT_GAME_ID, product.getGameId());
            values.put(PRODUCT_NAME, product.getName());
            values.put(PRODUCT_PRICE, product.getPrice());
            values.put(PRODUCT_STOCK, product.getStock());
            values.put(PRODUCT_STATUS, product.getStatus());
            values.put(PRODUCT_IMAGE_URL, product.getImageUrl());
            values.put(PRODUCT_TYPE, product.getType());
            values.put(PRODUCT_DESCRIPTION, product.getDescription());
            values.put(PRODUCT_CREATED_AT, product.getCreatedAt());
            values.put(PRODUCT_UPDATED_AT, product.getUpdatedAt());

            db.insert(TABLE_PRODUCTS, null, values);
        } catch (SQLException e) {
            Log.e("ProductHubDBHelper", "Error inserting product: " + e.getMessage());
        }
    }

    public ArrayList<Product> getAllProducts() {
        ArrayList<Product> productList = new ArrayList<>();

        try (SQLiteDatabase db = getReadableDatabase(); Cursor cursor = db.rawQuery("SELECT * FROM " + TABLE_PRODUCTS, null)) {
            if (cursor.moveToFirst()) {
                do {
                    Product product = new Product(
                            cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_ID)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_GAME_ID)),
                            cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_NAME)),
                            cursor.getDouble(cursor.getColumnIndexOrThrow(PRODUCT_PRICE)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_STOCK)),
                            cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_STATUS)),
                            cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_IMAGE_URL)),
                            cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_TYPE)),
                            cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_DESCRIPTION)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_CREATED_AT)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_UPDATED_AT))
                    );

                    productList.add(product);
                } while (cursor.moveToNext());
            }
        } catch (SQLException e) {
            Log.e("ProductHubDBHelper", "Error fetching products: " + e.getMessage());
        }
        return productList;
    }

    public Product getProductById(int productId) {
        try (SQLiteDatabase db = getReadableDatabase(); Cursor cursor = db.query(TABLE_PRODUCTS, null, PRODUCT_ID + "=?", new String[]{String.valueOf(productId)}, null, null, null)) {
            if (cursor != null && cursor.moveToFirst()) {
                return new Product(
                        cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_ID)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_GAME_ID)),
                        cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_NAME)),
                        cursor.getFloat(cursor.getColumnIndexOrThrow(PRODUCT_PRICE)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_STOCK)),
                        cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_STATUS)),
                        cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_IMAGE_URL)),
                        cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_TYPE)),
                        cursor.getString(cursor.getColumnIndexOrThrow(PRODUCT_DESCRIPTION)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_CREATED_AT)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(PRODUCT_UPDATED_AT))
                );
            }
        } catch (SQLException e) {
            Log.e("ProductHubDBHelper", "Error fetching product by ID: " + e.getMessage());
        }
        return null;
    }

    public boolean updateProduct(Product product) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(PRODUCT_GAME_ID, product.getGameId());
            values.put(PRODUCT_NAME, product.getName());
            values.put(PRODUCT_PRICE, product.getPrice());
            values.put(PRODUCT_STOCK, product.getStock());
            values.put(PRODUCT_STATUS, product.getStatus());
            values.put(PRODUCT_IMAGE_URL, product.getImageUrl());
            values.put(PRODUCT_TYPE, product.getType());
            values.put(PRODUCT_DESCRIPTION, product.getDescription());
            values.put(PRODUCT_CREATED_AT, product.getCreatedAt());
            values.put(PRODUCT_UPDATED_AT, product.getUpdatedAt());

            return db.update(TABLE_PRODUCTS, values, PRODUCT_ID + "=?", new String[]{String.valueOf(product.getId())}) > 0;
        } catch (SQLException e) {
            Log.e("ProductHubDBHelper", "Error updating product: " + e.getMessage());
            return false;
        }
    }

    public void removeAllProducts() {
        try (SQLiteDatabase db = getWritableDatabase()) {
            db.delete(TABLE_CARDS, null, null);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error removing all cards: " + e.getMessage());
        }
    }

    public boolean removeProductByID(int productId) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            return db.delete(TABLE_PRODUCTS, PRODUCT_ID + "=?", new String[]{String.valueOf(productId)}) > 0;
        } catch (SQLException e) {
            Log.e("ProductHubDBHelper", "Error deleting product: " + e.getMessage());
            return false;
        }
    }
    //endregion

    //region Listings
    public void insertListing(Listing listing) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(ID, listing.getId());
            values.put(SELLER_ID, listing.getSellerId());
            values.put(SELLER_USERNAME, listing.getSellerUsername());
            values.put(CARD_ID, listing.getCardId());
            values.put(CARD_NAME, listing.getCardName());
            values.put(CARD_IMAGE_URL, listing.getCardImageUrl());
            values.put(PRICE, listing.getPrice());
            values.put(CONDITION, listing.getCondition());
            values.put(STATUS, listing.getStatus());
            values.put(CREATED_AT, listing.getCreatedAt());
            values.put(UPDATED_AT, listing.getUpdatedAt());

            db.insert(TABLE_LISTINGS, null, values);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error inserting listing: " + e.getMessage());
        }
    }


    public ArrayList<Listing> getAllListings() {
        ArrayList<Listing> listingsList = new ArrayList<>();

        try (SQLiteDatabase db = getReadableDatabase(); Cursor cursor = db.rawQuery("SELECT * FROM " + TABLE_LISTINGS, null)) {
            if (cursor.moveToFirst()) {
                do {
                    Listing listing = new Listing(
                            cursor.getInt(cursor.getColumnIndexOrThrow(ID)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(SELLER_ID)),
                            cursor.getString(cursor.getColumnIndexOrThrow(SELLER_USERNAME)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(CARD_ID)),
                            cursor.getString(cursor.getColumnIndexOrThrow(CARD_NAME)),
                            cursor.getString(cursor.getColumnIndexOrThrow(CARD_IMAGE_URL)),
                            cursor.getDouble(cursor.getColumnIndexOrThrow(PRICE)),
                            cursor.getString(cursor.getColumnIndexOrThrow(CONDITION)),
                            cursor.getString(cursor.getColumnIndexOrThrow(STATUS)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(CREATED_AT)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(UPDATED_AT))
                    );

                    listingsList.add(listing);
                } while (cursor.moveToNext());
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error fetching listings: " + e.getMessage());
        }
        return listingsList;
    }

    public Listing getListingById(int listingId) {
        try (SQLiteDatabase db = getReadableDatabase();
             Cursor cursor = db.query(TABLE_LISTINGS, null, ID + "=?", new String[]{String.valueOf(listingId)}, null, null, null)) {

            if (cursor != null && cursor.moveToFirst()) {
                return new Listing(
                        cursor.getInt(cursor.getColumnIndexOrThrow(ID)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(SELLER_ID)),
                        cursor.getString(cursor.getColumnIndexOrThrow(SELLER_USERNAME)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(CARD_ID)),
                        cursor.getString(cursor.getColumnIndexOrThrow(CARD_NAME)),
                        cursor.getString(cursor.getColumnIndexOrThrow(CARD_IMAGE_URL)),
                        cursor.getDouble(cursor.getColumnIndexOrThrow(PRICE)),
                        cursor.getString(cursor.getColumnIndexOrThrow(CONDITION)),
                        cursor.getString(cursor.getColumnIndexOrThrow(STATUS)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(CREATED_AT)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(UPDATED_AT))
                );
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error fetching listing by ID: " + e.getMessage());
        }
        return null;
    }
  
    public boolean updateListing(Listing listing) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(ID, listing.getId());
            values.put(SELLER_ID, listing.getSellerId());
            values.put(SELLER_USERNAME, listing.getSellerUsername());
            values.put(CARD_ID, listing.getCardId());
            values.put(CARD_NAME, listing.getCardName());
            values.put(CARD_IMAGE_URL, listing.getCardImageUrl());
            values.put(PRICE, listing.getPrice());
            values.put(CONDITION, listing.getCondition());
            values.put(STATUS, listing.getStatus());
            values.put(CREATED_AT, listing.getCreatedAt());
            values.put(UPDATED_AT, listing.getUpdatedAt());

            return db.update(TABLE_LISTINGS, values, ID + "=?", new String[]{String.valueOf(listing.getId())}) > 0;
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error updating listing: " + e.getMessage());
            return false;
        }
    }

    public void removeAllListings() {
        try (SQLiteDatabase db = getWritableDatabase()) {
            db.delete(TABLE_LISTINGS, null, null);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error removing all listings: " + e.getMessage());
        }
    }

    public boolean removeListingById(int listingId) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            return db.delete(TABLE_LISTINGS, ID + " = ?", new String[]{String.valueOf(listingId)}) == 1;
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error deleting listing: " + e.getMessage());
            return false;
        }
    }
    //endregion

    //region Favorites
    public void insertFavorite(int cardId) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(FAVORITE_CARD_ID, cardId);
            db.insert(TABLE_FAVORITES, null, values);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error inserting favorite: " + e.getMessage());
        }
    }

    public void removeFavorite(int cardId) {
        try (SQLiteDatabase db = getWritableDatabase()){
            db.delete(TABLE_FAVORITES, FAVORITE_CARD_ID + " = ?", new String[]{String.valueOf(cardId)});
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error removing favorite: " + e.getMessage());
        }
    }

    public void removeAllFavorites() {
        try (SQLiteDatabase db = getWritableDatabase()) {
            db.delete(TABLE_FAVORITES, null, null);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error clearing favorites: " + e.getMessage());
        }
    }

    public List<Integer> getAllFavorites() {
        List<Integer> favoriteCardIds = new ArrayList<>();

        String query = "SELECT " + FAVORITE_CARD_ID + " FROM " + TABLE_FAVORITES;
        try (SQLiteDatabase db = getReadableDatabase();
             Cursor cursor = db.rawQuery(query, null)) {

            if (cursor.moveToFirst()) {
                do {
                    int cardId = cursor.getInt(cursor.getColumnIndexOrThrow(FAVORITE_CARD_ID));
                    favoriteCardIds.add(cardId);
                } while (cursor.moveToNext());
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error fetching favorites: " + e.getMessage());
        }
        return favoriteCardIds;
    }

    public boolean isFavorite(int cardId) {
        boolean isFavorite = false;

        String query = "SELECT 1 FROM " + TABLE_FAVORITES + " WHERE " + FAVORITE_CARD_ID + " = ?";
        try (SQLiteDatabase db = getReadableDatabase();
             Cursor cursor = db.rawQuery(query, new String[]{String.valueOf(cardId)})) {

            if (cursor != null && cursor.moveToFirst()) {
                isFavorite = true;
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error checking if card is favorite: " + e.getMessage());
        }

        return isFavorite;
    }
    //endregion
    //Cart
    public void insertCartItem(CartItem cartItem) {
        try (SQLiteDatabase db = getWritableDatabase()) {
            ContentValues values = new ContentValues();
            values.put(ITEM_ID, cartItem.getItemId());
            values.put(TYPE, cartItem.getType());
            values.put(QUANTITY, cartItem.getQuantity());

            db.insert(TABLE_CARTITEMS, null, values);
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error inserting cart item: " + e.getMessage());
        }
    }
    public ArrayList<CartItem> getAllCartItems() {
        ArrayList<CartItem> cartItemList = new ArrayList<>();

        try (SQLiteDatabase db = getReadableDatabase(); Cursor cursor = db.rawQuery("SELECT * FROM " + TABLE_CARTITEMS, null)) {
            if (cursor.moveToFirst()) {
                do {
                    CartItem cartItem = new CartItem(
                            cursor.getInt(cursor.getColumnIndexOrThrow(ITEM_ID)),
                            cursor.getString(cursor.getColumnIndexOrThrow(TYPE)),
                            cursor.getInt(cursor.getColumnIndexOrThrow(QUANTITY))
                    );

                    cartItemList.add(cartItem);
                } while (cursor.moveToNext());
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error fetching cart Items: " + e.getMessage());
        }
        return cartItemList;
    }

    public boolean isItemInCart(CartItem cartItem) {
        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = null;
        boolean isInCart = false;

        try {
            String selection = ITEM_ID + " = ? AND " + TYPE + " = ?";
            String[] selectionArgs = {
                    String.valueOf(cartItem.getItemId()),
                    cartItem.getType()
            };
            cursor = db.query(
                    TABLE_CARTITEMS,
                    null,
                    selection,
                    selectionArgs,
                    null,
                    null,
                    null
            );
            if (cursor != null && cursor.getCount() > 0) {
                isInCart = true;
            }
        } catch (SQLException e) {
            Log.e("CardHubDBHelper", "Error checking if item is in cart: " + e.getMessage());
        }
        if (cursor != null) {
            cursor.close();
        }
        db.close();

        return isInCart;
    }

    public void deleteCartItem(CartItem cartItem) {
        SQLiteDatabase db = null;
        try {
            db = this.getWritableDatabase();
            int rowsDeleted = db.delete(
                    TABLE_CARTITEMS,
                    ITEM_ID + "=? AND " + TYPE + "=? AND " + QUANTITY + "=?",
                    new String[]{
                            String.valueOf(cartItem.getItemId()),
                            cartItem.getType(),
                            String.valueOf(cartItem.getQuantity())
                    }
            );
            if (rowsDeleted == 0) {
                System.out.println("No matching item found to delete.");
            }
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (db != null) {
                db.close();
            }
        }
    }

    public void clearCart() {
        SQLiteDatabase db = null;
        try {
            db = this.getWritableDatabase();
            int rowsDeleted = db.delete(TABLE_CARTITEMS, null, null);
            System.out.println("Rows deleted: " + rowsDeleted);
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (db != null) {
                db.close();
            }
        }
    }

    public void updateCartItemQuantity(int cartItemId, int newQuantity) {
        try (SQLiteDatabase db = getWritableDatabase()){
            ContentValues values = new ContentValues();
            values.put(QUANTITY, newQuantity);
            db.update(TABLE_CARTITEMS, values, ITEM_ID +" = ?", new String[]{String.valueOf(cartItemId)});
        }
    }
    //endregion
}
