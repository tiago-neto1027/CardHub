package com.example.cardhub;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.cardhub.controllers.AuthController;

public class LoginFragment extends Fragment {

    private AuthController authController;
    private EditText etUsername, etPassword;

    public LoginFragment() {
        //Required empty constructor
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_login, container, false);

        etUsername = view.findViewById(R.id.etUsername);
        etPassword = view.findViewById(R.id.etPassword);
        Button btnLogin = view.findViewById(R.id.btnLogin);
        Button btnSignup = view.findViewById(R.id.btnSignUp);

        authController = new AuthController(getActivity());

        btnLogin.setOnClickListener(v -> onClickLogin());
        btnSignup.setOnClickListener(v -> navigateToSignupFragment());

        return view;
    }

    private void onClickLogin() {
        String username = etUsername.getText().toString();
        String password = etPassword.getText().toString();

        if (username.isEmpty() || password.isEmpty()) {
            Toast.makeText(getActivity(), "Username and password are required", Toast.LENGTH_SHORT).show();
            return;
        }

        authController.login(username, password, new AuthController.AuthCallback() {
            @Override
            public void onSuccess() {
                Toast.makeText(getActivity(), "Login successful", Toast.LENGTH_SHORT).show();
                navigateToAppMainActivity();
            }

            @Override
            public void onFailure(String errorMessage) {
                Toast.makeText(getActivity(), errorMessage, Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void navigateToSignupFragment() {
        if (getActivity() instanceof LoginActivity) {
            ((LoginActivity) getActivity()).loadFragment(new SignupFragment());
        }
    }

    private void navigateToAppMainActivity() {
        Intent intent = new Intent(getActivity(), AppMainActivity.class);
        startActivity(intent);
        requireActivity().finish();
    }
}
