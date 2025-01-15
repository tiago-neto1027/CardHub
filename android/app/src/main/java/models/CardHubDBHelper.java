package models;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class CardHubDBHelper extends SQLiteOpenHelper {
    private static final String DATABASE_NAME = "cardhub.db";
    private static final int DATABASE_VERSION = 1;
    private static CardHubDBHelper instance;

    //Cards
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

    // Products
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

    public static synchronized CardHubDBHelper getInstance(Context context) {
        if (instance == null) {
            instance = new CardHubDBHelper(context.getApplicationContext());
        }
        return instance;
    }

    public CardHubDBHelper(@Nullable Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        //Cards
        String createTableQuery = "CREATE TABLE " + TABLE_CARDS + " (" +
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
        sqLiteDatabase.execSQL(createTableQuery);

        //Products
        String createProductTableQuery = "CREATE TABLE " + TABLE_PRODUCTS + " (" +
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
        sqLiteDatabase.execSQL(createProductTableQuery);

    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_CARDS);
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
    //Region Products
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
                            cursor.getFloat(cursor.getColumnIndexOrThrow(PRODUCT_PRICE)),
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
}
