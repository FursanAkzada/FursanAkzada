package com.example.securityptpal;

import android.app.Activity;
import android.content.Intent;

import com.google.firebase.auth.FirebaseAuth;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class LogoutAccount {
    public static void logout(Activity activity){
        new SweetAlertDialog(activity, SweetAlertDialog.WARNING_TYPE)
                .setTitleText("LOGOUT")
                .setContentText("Are you sure want to logout ?")
                .setConfirmText("OK")
                .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                    @Override
                    public void onClick(SweetAlertDialog sDialog) {
                        FirebaseAuth.getInstance().signOut();
                        activity.startActivity(new Intent(activity,MainActivity2.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK));
                        Preferences.clearData(activity);
                        activity.finish();
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
}
