package models;

import android.content.Context;
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
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.cardhub.R;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;
import com.example.cardhub.utils.UserUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class RestAPIClient {

    private static RestAPIClient instance;
    private static RequestQueue requestQueue;

    private static Context context;

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

    //Login API doesnt implement a getRequest() because it uses the direct username and password that are passed to it
    public void loginAPI(final String username, final String password, final APIResponseCallback callback) {

        if(!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, R.string.no_internet, Toast.LENGTH_SHORT).show();
            return;
        }

        String url = Endpoints.getBaseUrl(context) + Endpoints.LOGIN_ENDPOINT;

        StringRequest loginRequest = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try{
                            JSONObject jsonResponse = new JSONObject(response);
                            int statusCode = jsonResponse.optInt("status", 0);

                            if (statusCode == 200) {
                                userUtils.saveCredentials(context, username, password);
                                callback.onSuccess(jsonResponse);
                            } else {
                                callback.onError("Invalid status code: " + statusCode);
                            }
                        } catch (JSONException e) {
                            callback.onError("Error parsing response");
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        callback.onError("Wrong credentials or network error.");
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

        requestQueue.add(loginRequest);
    }

    //region Private Base Methods
    private void getRequest(String endpoint, final APIResponseCallback callback) {
        String url = Endpoints.getBaseUrl(context) + endpoint;
        JsonObjectRequest jsonRequest = new JsonObjectRequest(
                Request.Method.GET, url, null,
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