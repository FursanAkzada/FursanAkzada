package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.model.User;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.AuthResult;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;

public class Register extends AppCompatActivity {
    TextView log;
    EditText email,pass1,pass2;
    String inputemail,password1,password2;
    Button Regis;
    FirebaseAuth mAuth;
    FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        mAuth = FirebaseAuth.getInstance();
        db = FirebaseFirestore.getInstance();
        log = findViewById(R.id.TVLogin);
        log.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(Register.this,Login2.class));
            }
        });

        email = findViewById(R.id.email);
        pass1 = findViewById(R.id.pass1);
        pass2 = findViewById(R.id.pass2);
        Regis = findViewById(R.id.arrow);
        Regis.setOnClickListener(new View.OnClickListener() {
             @Override
             public void onClick(View v) {
                 registrasi();
                }
            }
        );
    }

    public void registrasi(){
        inputemail = email.getText().toString();
        password1 = pass1.getText().toString();
        password2 = pass2.getText().toString();

        try{
            if (TextUtils.isEmpty(inputemail) || TextUtils.isEmpty(password1) || TextUtils.isEmpty(password2)){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                Alerter.create(Register.this)
                        .setTitle("Register Failed!")
                        .setText("Please fill email and password")
                        .setIcon(R.drawable.ic_close)
                        .setBackgroundColorRes(android.R.color.holo_red_dark)
                        .setDuration(2000)
                        .enableSwipeToDismiss()
                        .enableProgress(true)
                        .setProgressColorRes(R.color.design_default_color_primary)
                        .setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View view) {

                            }
                        })
                        .setOnShowListener(new OnShowAlertListener() {
                            @Override
                            public void onShow() {

                            }
                        })
                        .setOnHideListener(new OnHideAlertListener() {
                            @Override
                            public void onHide() {

                            }
                        })
                        .show();
            }else{
                if (password2.equals(password1)) {
                    mAuth.createUserWithEmailAndPassword(inputemail,password2)
                            .addOnCompleteListener(this, new OnCompleteListener<AuthResult>() {
                                @Override
                                public void onComplete(@NonNull Task<AuthResult> task) {
                                    if (task.isSuccessful()) {
                                        User user = new User(
                                                inputemail,
                                                password2,
                                                "subcon"
                                        );
                                        db.collection("users").document(mAuth.getUid()).set(user);
                                        // Sign in success, update UI with the signed-in user's information
                                        StyleableToast.makeText(getApplicationContext(),"Registration Success", Toast.LENGTH_LONG,R.style.logsuccess).show();
                                        startActivity(new Intent(Register.this,Login2.class));
                                    } else {
                                        // If sign in fails, display a message to the user.
                                        Alerter.create(Register.this)
                                                .setTitle("Registration Failed!")
                                                .setText("Error ! " + task.getException().getMessage())
                                                .setIcon(R.drawable.ic_clear)
                                                .setBackgroundColorRes(android.R.color.holo_red_dark)
                                                .setDuration(2000)
                                                .enableSwipeToDismiss()
                                                .enableProgress(true)
                                                .setProgressColorRes(R.color.design_default_color_primary)
                                                .setOnClickListener(new View.OnClickListener() {
                                                    @Override
                                                    public void onClick(View view) {

                                                    }
                                                })
                                                .setOnShowListener(new OnShowAlertListener() {
                                                    @Override
                                                    public void onShow() {

                                                    }
                                                })
                                                .setOnHideListener(new OnHideAlertListener() {
                                                    @Override
                                                    public void onHide() {

                                                    }
                                                })
                                                .show();
                                    }
                                }
                            });
                }else {
                    StyleableToast.makeText(getApplicationContext(), "Password is not same" ,Toast.LENGTH_LONG,R.style.resultfailed).show();
                }
            }
        }
        catch (Exception e){
            e.printStackTrace();
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startActivity(new Intent(this, Login2.class));
        finish();
    }
}