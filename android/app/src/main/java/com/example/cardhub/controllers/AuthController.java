package com.example.cardhub.controllers;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.example.cardhub.R;
import com.example.cardhub.utils.Endpoints;
import com.example.cardhub.utils.NetworkUtils;
import com.example.cardhub.utils.UserUtils;

import org.json.JSONException;
import org.json.JSONObject;

import models.RestAPIClient;

public class AuthController {

    private final Context context;
    private final UserUtils userUtils;

    public interface AuthCallback {
        void onSuccess();
        void onFailure(String errorMessage);
    }

    public AuthController(Context context) {
        this.context = context;
        this.userUtils = new UserUtils();
    }

    /*
    Tries to login the user in the API
    If the user doesn't have internet, it doesn't work

    Saves the user credentials in the shared preferences
    If for any reason the login fails, removes it

    The login works if the API Response is status:200
     */
    public void login(String username, String password, AuthCallback callback) {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, R.string.no_internet, Toast.LENGTH_SHORT).show();
            callback.onFailure("No internet connection");
            return;
        }

        // Save credentials temporarily for authentication headers
        userUtils.saveCredentials(context, username, password);

        RestAPIClient.getInstance(context).getRequestObject(Endpoints.LOGIN_ENDPOINT, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    int statusCode = response.getInt("status");
                    if (statusCode == 200) {
                        callback.onSuccess();
                    } else {
                        callback.onFailure("Invalid status code: " + statusCode);
                        userUtils.logout(context);
                    }
                } catch (JSONException e) {
                    callback.onFailure("Error parsing server response");
                    userUtils.logout(context);
                }
            }

            @Override
            public void onError(String error) {
                Log.d("AuthController Login", "onError: " + error);
                callback.onFailure("Either the credentials or the endpoint are wrong");
                userUtils.logout(context);
            }
        });
    }

    /*
    Tries to register the user
    If the signup fails sends back the error message
     */
    public void signup(String username, String password, String email, AuthCallback callback) {
        if (!NetworkUtils.hasInternet(context)) {
            Toast.makeText(context, R.string.no_internet, Toast.LENGTH_SHORT).show();
            callback.onFailure("No internet connection");
            return;
        }

        JSONObject jsonBody = new JSONObject();
        try {
            jsonBody.put("username", username);
            jsonBody.put("password", password);
            jsonBody.put("email", email);
        } catch (JSONException e) {
            callback.onFailure("Error sending user credentials, please try again later.");
            return;
        }

        RestAPIClient.getInstance(context).postRequest(Endpoints.SIGNUP_ENDPOINT, jsonBody, new RestAPIClient.APIResponseCallback() {
            @Override
            public void onSuccess(JSONObject response) {
                try {
                    int statusCode = response.getInt("status");

                    if (statusCode == 201) {
                        callback.onSuccess();
                    } else if (statusCode == 409) {
                        callback.onFailure(response.getString("message"));
                    } else {
                        callback.onFailure("Invalid status code: " + statusCode);
                    }

                } catch (JSONException e) {
                    callback.onFailure("Error parsing server response");
                }
            }

            @Override
            public void onError(String error) {
                Log.d("AuthController Signup", "onError: " + error);
                callback.onFailure("The endpoint might be wrong");
            }
        });
    }
}
