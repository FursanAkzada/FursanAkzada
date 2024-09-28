package com.example.securityptpal.employee;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.R;
import com.example.securityptpal.ShowQR;
import com.example.securityptpal.model.PermissionEmployee;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class DetailPermissionActivity extends AppCompatActivity {

    private TextView base, name, nip, division, date, necessity, place, timeout, timeback, division_approve, center_approve, departmen, status, txtEmployee;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    ImageView qrEmployee;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_permission);
        base = findViewById(R.id.permit_base);
        name = findViewById(R.id.permit_name);
        nip = findViewById(R.id.permit_nip);
        division = findViewById(R.id.permit_division);
        date = findViewById(R.id.permit_date);
        necessity = findViewById(R.id.permit_necessity);
        place = findViewById(R.id.permit_place);
        timeout = findViewById(R.id.permit_timeout);
        timeback = findViewById(R.id.permit_timeback);
        division_approve = findViewById(R.id.division_approval_status);
        center_approve = findViewById(R.id.center_approval);
        qrEmployee = findViewById(R.id.qrEmployee);
        txtEmployee = findViewById(R.id.txtEmployee);

        qrEmployee.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(DetailPermissionActivity.this, ShowQR.class));
            }
        });
        departmen = findViewById(R.id.permit_depart);
        status = findViewById(R.id.permit_status);

        PermissionEmployee permissionEmployee = getIntent().getParcelableExtra("permission");
        base.setText(permissionEmployee.getBase());
        name.setText(permissionEmployee.getName());
        nip.setText(permissionEmployee.getNip());
        division.setText(permissionEmployee.getDivision());
        date.setText(permissionEmployee.getDate());
        necessity.setText(permissionEmployee.getNecessity());
        place.setText(permissionEmployee.getPlace());
        timeout.setText(permissionEmployee.getTimeout());
        timeback.setText(permissionEmployee.getTimeback());
        departmen.setText(permissionEmployee.getDepartment());
        status.setText(permissionEmployee.getEmployee_status());
        if (permissionEmployee.getDivision_approval().equals("Pending")){
            division_approve.setText(permissionEmployee.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailPermissionActivity.this, SweetAlertDialog.WARNING_TYPE)
                            .setTitleText("PENDING")
                            .setContentText("Please wait the permission status")
                            .setConfirmText("Close")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });
        } else if (permissionEmployee.getDivision_approval().equals("Accepted")){
            division_approve.setText(permissionEmployee.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailPermissionActivity.this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("ACCEPTED")
                            .setContentText("Please continue to security permission")
                            .setConfirmText("Close")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });
        } else {
            division_approve.setText(permissionEmployee.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailPermissionActivity.this, SweetAlertDialog.ERROR_TYPE)
                            .setTitleText("REJECTED")
                            .setContentText("Sorry you can't continue to security permission")
                            .setConfirmText("Close")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });
        }

        if (permissionEmployee.getCenter_approval().equals("Pending")){
            center_approve.setText(permissionEmployee.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailPermissionActivity.this, SweetAlertDialog.WARNING_TYPE)
                            .setTitleText("PENDING")
                            .setContentText("Please wait")
                            .setConfirmText("Close")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });
        } else if (permissionEmployee.getCenter_approval().equals("Accepted")){
            center_approve.setText(permissionEmployee.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailPermissionActivity.this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("ACCEPTED")
                            .setContentText("Congratulations")
                            .setConfirmText("Close")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });
        } else {
            center_approve.setText(permissionEmployee.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailPermissionActivity.this, SweetAlertDialog.ERROR_TYPE)
                            .setTitleText("REJECTED")
                            .setContentText("Contact your department")
                            .setConfirmText("Close")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                }
            });
        }

        if (permissionEmployee.getDivision_approval().equals("Accepted") && permissionEmployee.getCenter_approval().equals("Accepted")){
            qrEmployee.setVisibility(View.VISIBLE);
            txtEmployee.setVisibility(View.VISIBLE);
        }
    }
}