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

    public CardHubDBHelper(@Nullable Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
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
}
