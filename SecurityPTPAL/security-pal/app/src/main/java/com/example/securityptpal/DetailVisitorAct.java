package com.example.securityptpal;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.example.securityptpal.employee.DetailPermissionActivity;
import com.example.securityptpal.model.PermissionEmployee;
import com.example.securityptpal.model.Visitor;

import org.apache.xmlbeans.impl.soap.Detail;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class DetailVisitorAct extends AppCompatActivity {
    private TextView name, company, phone, division, department, pic, necessity, date, timein, timeout, division_approve, center_approve, textqr, textpdf;
    ImageView qrVisitor, printpdf;

    public static final int REQUEST_STORAGE=101;
    String storagePermission[];

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_visitor);

        findId();
        storagePermission=new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE};
        displayData();

        name = findViewById(R.id.name);
        company = findViewById(R.id.company);
        phone = findViewById(R.id.phone);
        division = findViewById(R.id.division);
        department = findViewById(R.id.department);
        pic = findViewById(R.id.pic);
        necessity = findViewById(R.id.necessity);
        date = findViewById(R.id.date);
        timein = findViewById(R.id.timein);
        timeout = findViewById(R.id.timeout);
        division_approve = findViewById(R.id.division_approval_status);
        center_approve = findViewById(R.id.center_approval);
        textpdf = findViewById(R.id.textpdf);
        textqr = findViewById(R.id.textqr);
        printpdf = findViewById(R.id.printpdf);
        qrVisitor = findViewById(R.id.qrVisitor);

        qrVisitor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(DetailVisitorAct.this, ShowQR.class));
            }
        });

        Visitor visitor = getIntent().getParcelableExtra("permission");
        name.setText(visitor.getName());
        company.setText(visitor.getCompany());
        phone.setText(visitor.getPhone());
        division.setText(visitor.getDivision());
        department.setText(visitor.getDepartment());
        pic.setText(visitor.getPic());
        necessity.setText(visitor.getNecessity());
        date.setText(visitor.getDate());
        timein.setText(visitor.getTimein());
        timeout.setText(visitor.getTimeout());

        if (visitor.getDivision_approval().equals("Pending")){
            division_approve.setText(visitor.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailVisitorAct.this, SweetAlertDialog.WARNING_TYPE)
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
        } else if (visitor.getDivision_approval().equals("Accepted")){
            division_approve.setText(visitor.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailVisitorAct.this, SweetAlertDialog.SUCCESS_TYPE)
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
            division_approve.setText(visitor.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailVisitorAct.this, SweetAlertDialog.ERROR_TYPE)
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

        if (visitor.getCenter_approval().equals("Pending")){
            center_approve.setText(visitor.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailVisitorAct.this, SweetAlertDialog.WARNING_TYPE)
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
        } else if (visitor.getCenter_approval().equals("Accepted")){
            center_approve.setText(visitor.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailVisitorAct.this, SweetAlertDialog.SUCCESS_TYPE)
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
            center_approve.setText(visitor.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailVisitorAct.this, SweetAlertDialog.ERROR_TYPE)
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

        if (visitor.getDivision_approval().equals("Accepted") && visitor.getCenter_approval().equals("Accepted")){
            qrVisitor.setVisibility(View.VISIBLE);
            printpdf.setVisibility(View.VISIBLE);
            textpdf.setVisibility(View.VISIBLE);
            textqr.setVisibility(View.VISIBLE);
        }
    }

    private void displayData() {
        printpdf.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(DetailVisitorAct.this,IdcardDetailVisitor.class);
                intent.putExtra("name",name.getText().toString());
                intent.putExtra("company",company.getText().toString());
                intent.putExtra("phone",phone.getText().toString());
                intent.putExtra("division",division.getText().toString());
                intent.putExtra("department",department.getText().toString());
                intent.putExtra("pic",pic.getText().toString());
                intent.putExtra("necessity",necessity.getText().toString());
                intent.putExtra("date",date.getText().toString());
                intent.putExtra("timein",timein.getText().toString());
                intent.putExtra("timeout",timeout.getText().toString());
                startActivity(intent);
            }
        });
    }

    @RequiresApi(api = Build.VERSION_CODES.M)
    private void requestStoragePermission() {
        requestPermissions(storagePermission,REQUEST_STORAGE);
    }

    private boolean checkStoragePermission() {
        boolean result= ContextCompat.checkSelfPermission(this,Manifest.permission.WRITE_EXTERNAL_STORAGE)==(PackageManager.PERMISSION_GRANTED);
        return result;
    }

    private void findId() {
        printpdf=(ImageView)findViewById(R.id.printpdf);
        name = (TextView) findViewById(R.id.name);
        company = (TextView) findViewById(R.id.company);
        phone = (TextView) findViewById(R.id.phone);
        division = (TextView) findViewById(R.id.division);
        department = (TextView) findViewById(R.id.department);
        pic = (TextView) findViewById(R.id.pic);
        necessity = (TextView) findViewById(R.id.necessity);
        date = (TextView) findViewById(R.id.date);
        timein = (TextView) findViewById(R.id.timein);
        timeout = (TextView) findViewById(R.id.timeout);
    }
}