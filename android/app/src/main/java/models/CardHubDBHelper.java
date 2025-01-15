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
    private static CardHubDBHelper instance;
    private static final String DATABASE_NAME = "cardhub.db";
    private static final int DATABASE_VERSION = 2;

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

    //Table Listings
    private static final String TABLE_LISTINGS = "listings";
    private static final String SELLER_ID = "seller_id";
    private static final String SELLER_USERNAME = "seller_username";
    private static final String CARD_ID = "card_id";
    private static final String CARD_NAME = "card_name";
    private static final String CARD_IMAGE_URL = "card_image_url";
    private static final String PRICE = "price";
    private static final String CONDITION = "condition";

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
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_CARDS);
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_LISTINGS);
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

    // region Listings
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
                        cursor.getInt(cursor.getColumnIndexOrThrow(PRICE)),
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
}
