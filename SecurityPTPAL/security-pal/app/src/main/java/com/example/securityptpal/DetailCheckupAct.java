package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.example.securityptpal.model.CheckUp;
import com.example.securityptpal.model.Visitor;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class DetailCheckupAct extends AppCompatActivity {

    private TextView name, nip,division, department, status, date, type, others,division_approve, center_approve, textqr;
    ImageView qrCheckup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_checkup);

        name = findViewById(R.id.name_cu);
        nip = findViewById(R.id.nip_cu);
        division = findViewById(R.id.division_cu);
        department = findViewById(R.id.depart_cu);
        status = findViewById(R.id.status_cu);
        date = findViewById(R.id.date_cu);
        type = findViewById(R.id.type_cu);
        others = findViewById(R.id.others_cu);
        division_approve = findViewById(R.id.division_approval_status);
        center_approve = findViewById(R.id.center_approval);
        textqr = findViewById(R.id.txtqr);
        qrCheckup = findViewById(R.id.qrCheckup);

//        qrCheckup.setEnabled(false);


        qrCheckup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(DetailCheckupAct.this, ShowQR.class));
            }
        });

        CheckUp checkUp = getIntent().getParcelableExtra("permission_cu");
        name.setText(checkUp.getName());
        nip.setText(checkUp.getNip());
        division.setText(checkUp.getDivision());
        department.setText(checkUp.getDepartment());
        status.setText(checkUp.getStatus());
        date.setText(checkUp.getDate());
        type.setText(checkUp.getType());
        others.setText(checkUp.getOthers());

        if (checkUp.getDivision_approval().equals("Pending")){
            division_approve.setText(checkUp.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailCheckupAct.this, SweetAlertDialog.WARNING_TYPE)
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
        } else if (checkUp.getDivision_approval().equals("Accepted")){
            division_approve.setText(checkUp.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailCheckupAct.this, SweetAlertDialog.SUCCESS_TYPE)
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
            division_approve.setText(checkUp.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailCheckupAct.this, SweetAlertDialog.ERROR_TYPE)
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

        if (checkUp.getCenter_approval().equals("Pending")){
            center_approve.setText(checkUp.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailCheckupAct.this, SweetAlertDialog.WARNING_TYPE)
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
        } else if (checkUp.getCenter_approval().equals("Accepted")){
            center_approve.setText(checkUp.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailCheckupAct.this, SweetAlertDialog.SUCCESS_TYPE)
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
            center_approve.setText(checkUp.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailCheckupAct.this, SweetAlertDialog.ERROR_TYPE)
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

        if (checkUp.getDivision_approval().equals("Accepted") && checkUp.getCenter_approval().equals("Accepted")){
            qrCheckup.setVisibility(View.VISIBLE);
            textqr.setVisibility(View.VISIBLE);
        }
    }
}