package models;

import android.content.Context;
import android.util.Base64;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.cardhub.CardsFragment;
import com.example.cardhub.R;
import com.example.cardhub.adapters.CardAdapter;
import com.example.cardhub.listeners.CardsListener;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;
import com.example.cardhub.utils.UserUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class RestAPIClient {

    private static RestAPIClient instance;
    private static RequestQueue requestQueue;

    private static Context context;

    private CardsListener cardsListener;

    UserUtils userUtils = new UserUtils();

    public interface APIResponseCallback {
        void onSuccess(JSONObject response);
        void onError(String error);
    }

    private RestAPIClient(Context ctx) {
        context = ctx.getApplicationContext();
        requestQueue = Volley.newRequestQueue(context);
    }

    public static RestAPIClient getInstance(Context ctx) {
        if (instance == null) {
            instance = new RestAPIClient(ctx);
        }
        return instance;
    }

    //region Listeners
    public void setCardsListener(CardsListener cardsListener){
        this.cardsListener = cardsListener;
    }

    //endregion Listeners

    //region Public API Methods
    public void loginAPI(final APIResponseCallback callback){

        if(!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, R.string.no_internet, Toast.LENGTH_SHORT).show();
            return;
        }

        getRequest(Endpoints.LOGIN_ENDPOINT, callback);
    }

    public void getCards(){

        if(!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, R.string.no_internet, Toast.LENGTH_SHORT).show();
            //TODO: Get the cards from the local database
            return;
        }

       getRequest(Endpoints.CARD_ENDPOINT, new APIResponseCallback() {
           @Override
           public void onSuccess(JSONObject response) {
               try {
                   JSONArray cardsArray = response.getJSONArray("cards");
                   ArrayList<Card> cardsList = new ArrayList<>();

                   for (int i = 0; i < cardsArray.length(); i++) {
                       JSONObject cardJson = cardsArray.getJSONObject(i);

                       Card card = new Card(
                               cardJson.getInt("id"),
                               cardJson.getInt("game_id"),
                               cardJson.getString("name"),
                               cardJson.getString("rarity"),
                               cardJson.getString("image_url"),
                               cardJson.getString("status"),
                               cardJson.isNull("description") ? null : cardJson.optString("description"),
                               cardJson.getInt("created_at"),
                               cardJson.getInt("updated_at"),
                               cardJson.isNull("user_id") ? null : cardJson.optInt("user_id")
                       );

                       cardsList.add(card);
                   }

                   if (cardsListener != null) {
                       cardsListener.onRefreshCardsList(cardsList);
                   }

               } catch (JSONException e) {
                   Log.d("RestAPIClient", "cards on Catch: " + e.toString());
                   Toast.makeText(context, "Error parsing cards data", Toast.LENGTH_SHORT).show();
               }
           }

           @Override
           public void onError(String error) {
               Log.d("RestAPIClient", "Cards onError: " + error);
               Toast.makeText(context, error, Toast.LENGTH_SHORT).show();
           }
       });
    }

    //endregion Public API Methods

    //region Private Base Methods
    private void getRequest(String endpoint, final APIResponseCallback callback) {
        String url = Endpoints.getBaseUrl(context) + endpoint;
        JsonArrayRequest jsonRequest = new JsonArrayRequest(
                Request.Method.GET, url, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        try {
                            callback.onSuccess(new JSONObject().put("cards", response));
                        } catch (JSONException e) {
                            throw new RuntimeException(e);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        callback.onError(error.toString());
                    }
                }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                String credentials = userUtils.getUsername(context) + ":" + userUtils.getPassword(context);
                String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                headers.put("Authorization", auth);
                return headers;
            }
        };
        requestQueue.add(jsonRequest);
    }

    private void postRequest(String endpoint, JSONObject postData, final APIResponseCallback callback) {
        String url = Endpoints.getBaseUrl(context) + endpoint;
        JsonObjectRequest jsonRequest = new JsonObjectRequest(
                Request.Method.POST, url, postData,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        callback.onSuccess(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        callback.onError(error.toString());
                    }
                }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                UserUtils userUtils = new UserUtils();

                Map<String, String> headers = new HashMap<>();
                String credentials = userUtils.getUsername(context) + ":" + userUtils.getPassword(context);
                String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                headers.put("Authorization", auth);
                return headers;
            }
        };
        requestQueue.add(jsonRequest);
    }

    private void putRequest(String endpoint, JSONObject putData, final APIResponseCallback callback) {
        String url = Endpoints.getBaseUrl(context) + endpoint;
        JsonObjectRequest jsonRequest = new JsonObjectRequest(
                Request.Method.PUT, url, putData,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        callback.onSuccess(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        callback.onError(error.toString());
                    }
                }){
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                UserUtils userUtils = new UserUtils();

                Map<String, String> headers = new HashMap<>();
                String credentials = userUtils.getUsername(context) + ":" + userUtils.getPassword(context);
                String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                headers.put("Authorization", auth);
                return headers;
            }
        };
        requestQueue.add(jsonRequest);
    }

    private void deleteRequest(String endpoint, final APIResponseCallback callback) {
        String url = Endpoints.getBaseUrl(context) + endpoint;
        StringRequest stringRequest = new StringRequest(
                Request.Method.DELETE, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONObject jsonResponse = new JSONObject();
                            jsonResponse.put("response", response);
                            callback.onSuccess(jsonResponse);
                        } catch (Exception e) {
                            callback.onError(e.toString());
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        callback.onError(error.toString());
                    }
                }){
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                UserUtils userUtils = new UserUtils();

                Map<String, String> headers = new HashMap<>();
                String credentials = userUtils.getUsername(context) + ":" + userUtils.getPassword(context);
                String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                headers.put("Authorization", auth);
                return headers;
            }
        };
        requestQueue.add(stringRequest);
    }
    //endregion
}