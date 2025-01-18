package com.example.cardhub.controllers;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;
import com.example.cardhub.utils.UserUtils;

import org.json.JSONException;
import org.json.JSONObject;

import models.CardHubDBHelper;
import models.RestAPIClient;

public class UserController {
    private final Context context;
    private final UserUtils userUtils;

    public UserController(Context context){
        this.context = context;
        this.userUtils = new UserUtils();
    }

    //Gets user email and sends it on callback
    public interface UserEmailCallback {
        void onEmailReceived(String email);
        void onError(String error);
    }
    public void getEmail(UserEmailCallback callback){
        if(!NetworkUtils.hasInternet(context)){
            String email = userUtils.getEmail(context);
            callback.onEmailReceived(email);
            return;
        }

        String endpoint = Endpoints.USER_ENDPOINT + "/email";
        RestAPIClient.getInstance(context).getRequestObject(endpoint, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try{
                    String email = response.getString("email");
                    callback.onEmailReceived(email);
                } catch (JSONException e){
                    callback.onError("Error fetching your email.");
                }
            }
            @Override
            public void onError(String error) {
                callback.onError("Error fetching your email.");
            }
        });
    }

    //Updates the user Email
    public void updateEmail(String email, final UserEmailCallback callback) {
        if (!NetworkUtils.hasInternet(context)) {
            callback.onError("No internet connection. Cannot send email.");
            return;
        }

        if (!isValidEmail(email)) {
            callback.onError("Invalid email format.");
            return;
        }

        String endpoint = Endpoints.USER_ENDPOINT + "/email";
        JSONObject postData = new JSONObject();
        try {
            postData.put("email", email);
        } catch (JSONException e) {
            callback.onError("Error creating JSON for email.");
            return;
        }

        RestAPIClient.getInstance(context).postRequest(endpoint, postData, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    if(response.has("status") && response.getString("status").equals("fail")){
                        callback.onError(response.optString("message"));
                    } else if (response.has("email")) {
                        String email = response.getString("email");
                        userUtils.setEmail(context, email);
                        callback.onEmailReceived(email);
                    } else {
                        callback.onError("Unexpected server response.");
                    }
                } catch (JSONException e) {
                    callback.onError("Error parsing server response.");
                }
            }
            @Override
            public void onError(String error) {
                callback.onError("Error sending email to the server.");
            }
        });
    }

    private boolean isValidEmail(String email) {
        String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";
        return email != null && email.matches(emailPattern);
    }

    public interface UserUsernameCallback {
        void onUsernameReceived(String email);
        void onError(String error);
    }
    public void updateUsername(String username, final UserUsernameCallback callback) {
        if (!NetworkUtils.hasInternet(context)) {
            callback.onError("No internet connection. Cannot send email.");
            return;
        }

        String endpoint = Endpoints.USER_ENDPOINT + "/username";
        JSONObject postData = new JSONObject();
        try {
            postData.put("username", username);
        } catch (JSONException e) {
            callback.onError("Error creating JSON for username.");
            return;
        }

        RestAPIClient.getInstance(context).postRequest(endpoint, postData, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    if(response.has("status") && response.getString("status").equals("fail")){
                        callback.onError(response.optString("message"));
                    } else if (response.has("username")) {
                        String username = response.getString("username");
                        userUtils.setUsername(context, username);
                        callback.onUsernameReceived(username);
                    } else {
                        callback.onError("Unexpected server response.");
                    }
                } catch (JSONException e) {
                    callback.onError("Error parsing server response.");
                }
            }
            @Override
            public void onError(String error) {
                callback.onError("Error sending username to the server.");
            }
        });
    }

    public void logOut() {
        CardHubDBHelper dbHelper = CardHubDBHelper.getInstance(context);
        dbHelper.removeAllFavorites();
        //TODO: removeAllCartItems();

        userUtils.logout(context);

        Toast.makeText(context, "Logged out successfully!", Toast.LENGTH_SHORT).show();
    }
}
