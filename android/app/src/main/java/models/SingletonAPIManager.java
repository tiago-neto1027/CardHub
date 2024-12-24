package models;

import android.content.Context;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.util.Base64;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.Volley;
import com.android.volley.toolbox.StringRequest;
import com.example.cardhub.R;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;


public class SingletonAPIManager {
    private static SingletonAPIManager instance = null;
    private static RequestQueue volleyQueue = null;

    //Endpoints
    private static final String BASE_URL = "http://13.39.156.210:8080/api";
    private static final String LOGIN_ENDPOINT = "/auth/login";

    //Listeners


    // SharedPreferences
    private static final String SHARED_PREFS_NAME = "AppPreferences";
    private static final String USERNAME_KEY = "Username";
    private static final String PASSWORD_KEY = "Password";

    //Credentials
    private String username;
    private String password;

    //Singleton Constructor
    public static synchronized SingletonAPIManager getInstance(Context context){
        if(instance == null){
            instance = new SingletonAPIManager(context);
            volleyQueue = Volley.newRequestQueue(context);
        }
        return instance;
    }

    public SingletonAPIManager(Context context) {
        loadCredentialsFromCache(context);
    }

    private void loadCredentialsFromCache(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREFS_NAME, Context.MODE_PRIVATE);
        username = sharedPreferences.getString(USERNAME_KEY, null);
        password = sharedPreferences.getString(PASSWORD_KEY, null);
    }

    private void saveCredentialsToCache(Context context, String username, String password) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREFS_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(USERNAME_KEY, username);
        editor.putString(PASSWORD_KEY, password);
        editor.apply();
    }

    //region API Methods

    public void loginAPI(final String username, final String password, final Context context) {

        if(!hasInternet(context)) {
            Toast.makeText(context, R.string.no_internet, Toast.LENGTH_SHORT).show();
            return;
        }

        String url = BASE_URL + LOGIN_ENDPOINT;

        StringRequest loginRequest = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try{
                            JSONObject jsonResponse = new JSONObject(response);
                            int statusCode = jsonResponse.optInt("status", 0);

                            if (statusCode == 200) {
                                saveCredentialsToCache(context, username, password);
                                Toast.makeText(context, "Logged in successfully.", Toast.LENGTH_SHORT).show();
                            }

                        } catch (JSONException e) {
                            Toast.makeText(context, "Error parsing response", Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, "Wrong credentials.", Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                String credentials = username + ":" + password;
                String auth = "Basic " + Base64.encodeToString(credentials.getBytes(), Base64.NO_WRAP);
                headers.put("Authorization", auth);
                return headers;
            }
        };

        volleyQueue.add(loginRequest);
    }

    //endregion

    public static boolean hasInternet(Context context)
    {
        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = cm.getActiveNetworkInfo();

        return networkInfo != null && networkInfo.isConnected();
    }

    public boolean isLoggedIn() {
        return username != null && password != null;
    }

    public void logout(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(SHARED_PREFS_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.remove(USERNAME_KEY);
        editor.remove(PASSWORD_KEY);
        editor.apply();

        Toast.makeText(context, "Logged out", Toast.LENGTH_SHORT).show();
    }
}
