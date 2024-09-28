package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.FirebaseAuth;
import com.muddzdev.styleabletoast.StyleableToast;

public class ForgetPassword extends AppCompatActivity {
//    ProgressDialog dialog;
    FirebaseAuth auth;
    private Button forget;
    private EditText emailedttext;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forget_password);
        forget = (Button) findViewById(R.id.sendforget);
        emailedttext = (EditText) findViewById(R.id.emailforget);

        auth = FirebaseAuth.getInstance();

//        dialog = new ProgressDialog(ForgetPassword.this);
//        dialog.setCancelable(false);
//        dialog.setMessage("Loading...");

        forget.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                forgetPassword();
            }
        });
    }

//    private Boolean validateEmail(){
//        String val = email.getText().toString();
//        String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";
//
//        if (val.isEmpty()){
//            Toast.makeText(ForgetPassword.this, "Field can't be empty", Toast.LENGTH_LONG).show();
//        } else if(!val.matches(emailPattern)){
//            Toast.makeText(ForgetPassword.this, "Invalid email address", Toast.LENGTH_LONG).show();
//        } else{
//            Toast.makeText(ForgetPassword.this, "Null", Toast.LENGTH_LONG).show();
//        }
//        return null;
//    }

    private void forgetPassword(){
//        if (!validateEmail())
//        {
//            return;
//        }
//
//        dialog.show();
//
//        auth.sendPasswordResetEmail(email.getText().toString()).addOnCompleteListener(new OnCompleteListener<Void>() {
//            @Override
//            public void onComplete(@NonNull Task<Void> task) {
//                dialog.dismiss();
//                if (task.isSuccessful())
//                {
//                    startActivity(new Intent(ForgetPassword.this, Login2.class));
//                    finish();
//                    Toast.makeText(ForgetPassword.this, "Please check your Email Address", Toast.LENGTH_LONG).show();
//                }
//                else{
//                    Toast.makeText(ForgetPassword.this, "Enter correct email", Toast.LENGTH_LONG).show();
//                }
//            }
//        }).addOnFailureListener(new OnFailureListener() {
//            @Override
//            public void onFailure(@NonNull Exception e) {
//                Toast.makeText(ForgetPassword.this, e.getMessage(), Toast.LENGTH_LONG).show();
//            }
//        });
        String email = emailedttext.getText().toString();

        if(email.isEmpty()){
            emailedttext.setError("Email is required!");
            emailedttext.requestFocus();
            return;
        }

        if(!Patterns.EMAIL_ADDRESS.matcher(email).matches()){
            emailedttext.setError("Please provide valid email!");
            emailedttext.requestFocus();
            return;
        }

        auth.sendPasswordResetEmail(email).addOnCompleteListener(new OnCompleteListener<Void>() {
            @Override
            public void onComplete(@NonNull Task<Void> task) {
                if (task.isSuccessful())
                {
                    StyleableToast.makeText(getApplicationContext(), "Please check your Email Address", Toast.LENGTH_LONG, R.style.result).show();
                }
                else{
                    StyleableToast.makeText(getApplicationContext(), "Enter correct email", Toast.LENGTH_LONG, R.style.warning).show();
                }
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                StyleableToast.makeText(ForgetPassword.this, e.getMessage(), Toast.LENGTH_LONG, R.style.resultfailed).show();
            }
        });
    }
}