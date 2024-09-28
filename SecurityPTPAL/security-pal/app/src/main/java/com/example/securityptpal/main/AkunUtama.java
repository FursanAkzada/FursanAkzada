package com.example.securityptpal.main;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;

import com.example.securityptpal.AboutUs;
import com.example.securityptpal.FAQ;
import com.example.securityptpal.LogoutAccount;
import com.example.securityptpal.MainActivity;
import com.example.securityptpal.MainActivity2;
import com.example.securityptpal.R;
import com.example.securityptpal.UtamaDataBarang;
import com.example.securityptpal.UtamaDataCheckup;
import com.example.securityptpal.UtamaDataGuest;
import com.example.securityptpal.UtamaDataParksub;
import com.example.securityptpal.UtamaDataSubcon;
import com.example.securityptpal.UtamaDataVisitor;
import com.example.securityptpal.Utama_Data_Cometoolate;
import com.example.securityptpal.division.ExitPermissionActivity;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class AkunUtama extends AppCompatActivity {

    private ImageView imgSignOut;
    DrawerLayout drawerLayout;
    ImageView btMenu;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_akun_utama);
        imgSignOut = findViewById(R.id.sign_out_main_account);
        drawerLayout = findViewById(R.id.drawer_layout);
        btMenu = findViewById(R.id.bt_menu);

        btMenu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                drawerLayout.openDrawer(GravityCompat.START);
            }
        });

        imgSignOut.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                LogoutAccount.logout(AkunUtama.this);
            }
        });
    }
    public void ClickLogo(View view){
        closeDrawer(drawerLayout);
    }

    public static void closeDrawer(DrawerLayout drawerLayout){
        if(drawerLayout.isDrawerOpen(GravityCompat.START)){
            drawerLayout.closeDrawer(GravityCompat.START);
        }
    }

    public void ClickMain(View view){
        redirectActivity(this, AkunUtama.class);
        finish();
    }

    public void ClickEmployee(View view){
        redirectActivity(this, UtamaDataEmployee.class);
    }

    public void ClickComelate(View view){
        redirectActivity(this, Utama_Data_Cometoolate.class);
    }

    public void ClickCheckup(View view){ redirectActivity(this, UtamaDataCheckup.class); }

    public void ClickSubcon(View view){ redirectActivity(this, UtamaDataSubcon.class); }

    public void ClickParksub(View view){ redirectActivity(this, UtamaDataParksub.class); }

    public void ClickGuest(View view){
        redirectActivity(this, UtamaDataGuest.class);
    }

    public void ClickVisitor(View view){
        redirectActivity(this, UtamaDataVisitor.class);
    }

    public void ClickGoods(View view){
        redirectActivity(this, UtamaDataBarang.class);
    }

    public void ClickEdit(View view){
        redirectActivity(this, MainDivisionActivity.class);
    }

    public static void redirectActivity(Activity activity, Class aClass){
        Intent intent = new Intent(activity,aClass);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        activity.startActivity(intent);
    }

    public void ClickExit(View view){
        exit(this);
    }

    public static void exit(Activity activity){
        new SweetAlertDialog(activity, SweetAlertDialog.WARNING_TYPE)
                .setTitleText("EXIT")
                .setContentText("Are you sure want to exit ?")
                .setConfirmText("OK")
                .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                    @Override
                    public void onClick(SweetAlertDialog sDialog) {
                        activity.finishAffinity();
                        System.exit(0);
                    }
                })
                .setCancelButton("CANCEL", new SweetAlertDialog.OnSweetClickListener() {
                    @Override
                    public void onClick(SweetAlertDialog sDialog) {
                        sDialog.dismissWithAnimation();
                    }
                })
                .show();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startActivity(new Intent(this, MainActivity2.class));
        finish();
    }
}