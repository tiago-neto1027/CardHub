package com.example.cardhub;

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

public class SignupFragment extends Fragment {

    private AuthController authController;
    private EditText etUsername, etEmail, etPassword, etConfirmPassword;

    public SignupFragment() {
        // Required empty public constructor
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_signup, container, false);

        etUsername = view.findViewById(R.id.etSignupUsername);
        etEmail = view.findViewById(R.id.etSignupEmail);
        etPassword = view.findViewById(R.id.etSignupPassword);
        etConfirmPassword = view.findViewById(R.id.etSignupConfirmPassword);
        Button btnSignup = view.findViewById(R.id.btnSignup);
        Button btnLogin = view.findViewById(R.id.btnLogin);

        authController = new AuthController(getActivity());

        btnSignup.setOnClickListener(v -> onClickSignup());
        btnLogin.setOnClickListener(v -> navigateToLoginFragment());

        return view;
    }

    private void onClickSignup() {
        String username = etUsername.getText().toString();
        String email = etEmail.getText().toString();
        String password = etPassword.getText().toString();
        String confirmPassword = etConfirmPassword.getText().toString();

        if (username.isEmpty() || email.isEmpty() || password.isEmpty() || confirmPassword.isEmpty()) {
            Toast.makeText(getActivity(), "All fields are required", Toast.LENGTH_SHORT).show();
            return;
        }

        if (!password.equals(confirmPassword)){
            Toast.makeText(getActivity(), "Passwords don't match", Toast.LENGTH_SHORT).show();
            return;
        }

        authController.signup(username, password, email, new AuthController.AuthCallback() {
            @Override
            public void onSuccess() {
                Toast.makeText(getActivity(), "Registration successful\nPlease verify your email", Toast.LENGTH_SHORT).show();
                navigateToLoginFragment();
            }

            @Override
            public void onFailure(String errorMessage) {
                Toast.makeText(getActivity(), errorMessage, Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void navigateToLoginFragment() {
        if (getActivity() instanceof LoginActivity) {
            ((LoginActivity) getActivity()).loadFragment(new LoginFragment());
        }
    }
}
