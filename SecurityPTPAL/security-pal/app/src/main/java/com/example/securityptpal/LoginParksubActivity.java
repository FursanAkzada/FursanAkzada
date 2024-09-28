package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Switch;
import android.widget.Toast;

import com.example.securityptpal.division.AkunDivisi;
import com.example.securityptpal.employee.Employee;
import com.example.securityptpal.main.AkunUtama;
import com.example.securityptpal.main.UtamaDataEmployee;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.AuthResult;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;

public class LoginParksubActivity extends AppCompatActivity {

    EditText edtEmail, edtPassword;
    Button login;
    Switch active;
    String userID;
    FirebaseAuth mAuth;
    FirebaseFirestore db;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login_parksub);
        edtEmail = findViewById(R.id.email_parksub);
        edtPassword = findViewById(R.id.password_parksub);
        active = findViewById(R.id.active_parksub);
        login = findViewById(R.id.btn_login_parksub);
        mAuth = FirebaseAuth.getInstance();
        db = FirebaseFirestore.getInstance();
        login.setOnClickListener(view -> {
            if (TextUtils.isEmpty(edtEmail.getText().toString()) ||
                    TextUtils.isEmpty(edtPassword.getText().toString())){
                Alerter.create(LoginParksubActivity.this)
                        .setTitle("Empty Data Provided!")
                        .setText("Please fill the data correctly")
                        .setIcon(R.drawable.ic_priority)
                        .setBackgroundColorRes(android.R.color.holo_orange_light)
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
            else {
                FirebaseAuth.getInstance().signOut();
                Preferences.clearData(LoginParksubActivity.this);
                mAuth.signInWithEmailAndPassword(edtEmail.getText().toString(), edtPassword.getText().toString())
                        .addOnCompleteListener(LoginParksubActivity.this, new OnCompleteListener<AuthResult>() {
                            @Override
                            public void onComplete(@NonNull Task<AuthResult> task) {
                                if (task.isSuccessful()) {
                                    userID = mAuth.getCurrentUser().getUid();
                                    DocumentReference documentReference = db.collection("users").document(userID);
                                    documentReference.addSnapshotListener(LoginParksubActivity.this, new EventListener<DocumentSnapshot>() {
                                        @Override
                                        public void onEvent(@Nullable DocumentSnapshot value, @Nullable FirebaseFirestoreException error) {
                                            if (active.isChecked()) {
                                                if (value.getString("role").equals("subcon")){
                                                    Preferences.setDataLogin(LoginParksubActivity.this, true);
                                                    Preferences.setDataRole(LoginParksubActivity.this, "subcon");
                                                    StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                    startActivity(new Intent(LoginParksubActivity.this, ParkingSubcontractor.class));
                                                    finish();
                                                } else if (value.getString("role").equals("main")){
                                                    Preferences.setDataLogin(LoginParksubActivity.this, true);
                                                    Preferences.setDataRole(LoginParksubActivity.this, "main");
                                                    StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                    startActivity(new Intent(LoginParksubActivity.this, ParkingSubcontractor.class));
                                                    finish();
                                                } else if (value.getString("role").equals("security")){
                                                    Preferences.setDataLogin(LoginParksubActivity.this, true);
                                                    Preferences.setDataRole(LoginParksubActivity.this, "security");
                                                    StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                    startActivity(new Intent(LoginParksubActivity.this, ParkingSubcontractor.class));
                                                    finish();
                                                }
                                            }else {
                                                if (value.getString("role").equals("subcon")){
                                                    Preferences.setDataLogin(LoginParksubActivity.this, false);
                                                    StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                    startActivity(new Intent(LoginParksubActivity.this, ParkingSubcontractor.class));
                                                    finish();
                                                } else if (value.getString("role").equals("main")){
                                                    Preferences.setDataLogin(LoginParksubActivity.this, false);
                                                    StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                    startActivity(new Intent(LoginParksubActivity.this, ParkingSubcontractor.class));
                                                    finish();
                                                } else if (value.getString("role").equals("security")){
                                                    Preferences.setDataLogin(LoginParksubActivity.this, false);
                                                    StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                    startActivity(new Intent(LoginParksubActivity.this, ParkingSubcontractor.class));
                                                    finish();
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    Alerter.create(LoginParksubActivity.this)
                                            .setTitle("Login Failed!")
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
            }
        });
    }

    @Override
    protected void onStart() {
        super.onStart();
        if (Preferences.getDataLogin(this)) {
            if (Preferences.getDataRole(this).equals("subcon")) {
                startActivity(new Intent(this, ParkingSubcontractor.class));
                finish();
            }else if (Preferences.getDataRole(this).equals("main")) {
                startActivity(new Intent(this, ParkingSubcontractor.class));
                finish();
            } else if (Preferences.getDataRole(this).equals("security")) {
                startActivity(new Intent(this, ParkingSubcontractor.class));
                finish();
            }
        }
    }
}