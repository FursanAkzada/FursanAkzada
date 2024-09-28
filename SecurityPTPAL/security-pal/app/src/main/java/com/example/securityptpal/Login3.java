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

public class Login3 extends AppCompatActivity {

    EditText edtEmail, edtPassword;
    Button arrow;
    Switch active;
    String userID;
    FirebaseAuth mAuth;
    FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login3);
        edtEmail = findViewById(R.id.email_employee3);
        edtPassword = findViewById(R.id.password_employee3);
        active = findViewById(R.id.active3);
        arrow = findViewById(R.id.arrow3);
        mAuth = FirebaseAuth.getInstance();
        db = FirebaseFirestore.getInstance();
        arrow.setOnClickListener(new View.OnClickListener() {
                                     @Override
                                     public void onClick(View v) {
                                         //validate inputs
                                         if (TextUtils.isEmpty(edtEmail.getText().toString()) ||
                                                 TextUtils.isEmpty(edtPassword.getText().toString())){
                                             Alerter.create(Login3.this)
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

                                             mAuth.signInWithEmailAndPassword(edtEmail.getText().toString(), edtPassword.getText().toString())
                                                     .addOnCompleteListener(Login3.this, new OnCompleteListener<AuthResult>() {
                                                         @Override
                                                         public void onComplete(@NonNull Task<AuthResult> task) {
                                                             if (task.isSuccessful()) {
                                                                 userID = mAuth.getCurrentUser().getUid();
                                                                 DocumentReference documentReference = db.collection("users").document(userID);
                                                                 documentReference.addSnapshotListener(Login3.this, new EventListener<DocumentSnapshot>() {
                                                                     @Override
                                                                     public void onEvent(@Nullable DocumentSnapshot value, @Nullable FirebaseFirestoreException error) {
                                                                         if (active.isChecked()) {
                                                                             if (value.getString("role").equals("employlate")) {
                                                                                 Preferences.setDataLogin(Login3.this, true);
                                                                                 Preferences.setDataRole(Login3.this, "employlate");
                                                                                 StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                                                 openEmploylate();
                                                                             }
                                                                         }else {
                                                                             if (value.getString("role").equals("employlate")) {
                                                                                 Preferences.setDataLogin(Login3.this, false);
                                                                                 StyleableToast.makeText(getApplicationContext(),"Login Success", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                                                                 openEmploylate();
                                                                             }
                                                                         }
                                                                     }
                                                                 });
                                                             } else {
                                                                 Alerter.create(Login3.this)
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
                                     }
                                 }
        );
    }

    public void openEmploylate() {
        Intent intent = new Intent(this, CometooLate.class);
        startActivity(intent);
        finish();
    }

    @Override
    protected void onStart() {
        super.onStart();
        if (Preferences.getDataLogin(this)) {
            if (Preferences.getDataRole(this).equals("employlate")) {
                startActivity(new Intent(this, CometooLate.class));
                finish();
            }
        }
    }
}