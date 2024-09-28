package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.adapter.GuestAdapter;
import com.example.securityptpal.adapter.GuestMemberAdapter;
import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.adapter.SubconEmployeeAdapter;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.MemberGuest;
import com.example.securityptpal.model.Subcon;
import com.example.securityptpal.model.Visitor;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.google.firebase.storage.StorageReference;
import com.google.firebase.storage.UploadTask;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;

import org.w3c.dom.Text;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class DetailGuestAct extends AppCompatActivity implements OnPermitListener {
    private TextView name, company, phone, division, department, pic, necessity, date, timeIn, timeOut, division_approve, center_approve, textqr, textpdf;
    ImageView qrGuest, printpdf;
    public static final int REQUEST_STORAGE=101;
    String storagePermission[];
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<MemberGuest> list = new ArrayList<>();
    private GuestMemberAdapter guestMemberAdapter;
    private RecyclerView recyclerView;
    Guest guest;
    ProgressDialog progressDialog;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_guest);

        progressDialog = new ProgressDialog(DetailGuestAct.this);
        findId();
        storagePermission=new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE};
        displayData();

        name = findViewById(R.id.name_guest);
        company = findViewById(R.id.company_guest);
        phone = findViewById(R.id.phone_guest);
        division = findViewById(R.id.division_guest);
        department = findViewById(R.id.department_guest);
        pic = findViewById(R.id.pic_guest);
        necessity = findViewById(R.id.necessity_guest);
        date = findViewById(R.id.date_guest);
        timeIn = findViewById(R.id.timein_guest);
        timeOut = findViewById(R.id.timeout_guest);
        division_approve = findViewById(R.id.division_approval_status);
        center_approve = findViewById(R.id.center_approval);
        textpdf = findViewById(R.id.textpdf);
        textqr = findViewById(R.id.textqr);
        recyclerView = findViewById(R.id.rv_membergu_list);

        qrGuest = findViewById(R.id.qrGuest);

        qrGuest.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(DetailGuestAct.this, ShowQR.class));
            }
        });

        guest = getIntent().getParcelableExtra("permission_gue");
        name.setText(guest.getName());
        company.setText(guest.getCompany());
        phone.setText(guest.getPhone());
        division.setText(guest.getDivision());
        department.setText(guest.getDepartment());
        pic.setText(guest.getPic());
        necessity.setText(guest.getNecessity());
        date.setText(guest.getDate());
        timeIn.setText(guest.getTimeIn());
        timeOut.setText(guest.getTimeOut());

        if (guest.getDivision_approval().equals("Pending")){
            division_approve.setText(guest.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailGuestAct.this, SweetAlertDialog.WARNING_TYPE)
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
        } else if (guest.getDivision_approval().equals("Accepted")){
            division_approve.setText(guest.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailGuestAct.this, SweetAlertDialog.SUCCESS_TYPE)
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
            division_approve.setText(guest.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
            division_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailGuestAct.this, SweetAlertDialog.ERROR_TYPE)
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

        if (guest.getCenter_approval().equals("Pending")){
            center_approve.setText(guest.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailGuestAct.this, SweetAlertDialog.WARNING_TYPE)
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
        } else if (guest.getCenter_approval().equals("Accepted")){
            center_approve.setText(guest.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailGuestAct.this, SweetAlertDialog.SUCCESS_TYPE)
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
            center_approve.setText(guest.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
            center_approve.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    new SweetAlertDialog(DetailGuestAct.this, SweetAlertDialog.ERROR_TYPE)
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

        if (guest.getDivision_approval().equals("Accepted") && guest.getCenter_approval().equals("Accepted")){
            printpdf.setVisibility(View.VISIBLE);
            qrGuest.setVisibility(View.VISIBLE);
            textqr.setVisibility(View.VISIBLE);
            textpdf.setVisibility(View.VISIBLE);
        }
        guestMemberAdapter = new GuestMemberAdapter(this, list, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(guestMemberAdapter);
        showAllData();
    }

    private void showAllData() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("permission_guest").document(guest.getId()).collection("members").addSnapshotListener(new EventListener<QuerySnapshot>() {
            @SuppressLint("NotifyDataSetChanged")
            @Override
            public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                list.clear();
                if (value != null) {
                    for (QueryDocumentSnapshot document : value) {
                        MemberGuest memberGuest = new MemberGuest(
                                document.getId(),
                                document.getString("name")
                        );
                        list.add(memberGuest);
                    }
                    guestMemberAdapter.notifyDataSetChanged();
                } else {
                    StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                }
                progressDialog.dismiss();
            }
        });
    }

    @Override
    public void onPermitClick(int position) {
        Intent intent = new Intent(DetailGuestAct.this, DetailGuestMemberActivity.class);
        intent.putExtra("GUEST_MEMBER_DETAIL", list.get(position));
        intent.putExtra("MEMBER_DATA", guest);
        startActivity(intent);
    }

    private void displayData() {
        printpdf.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                    Intent intent = new Intent(DetailGuestAct.this, IdcardDetailGuest.class);
                    intent.putExtra("docgu_name", name.getText().toString());
                    intent.putExtra("docgu_comp", company.getText().toString());
                    intent.putExtra("docgu_phone", phone.getText().toString());
                    intent.putExtra("docgu_div", division.getText().toString());
                    intent.putExtra("docgu_dep", department.getText().toString());
                    intent.putExtra("docgu_pic", pic.getText().toString());
                    intent.putExtra("docgu_nec", necessity.getText().toString());
                    intent.putExtra("docgu_date", date.getText().toString());
                    intent.putExtra("docgu_in", timeIn.getText().toString());
                    intent.putExtra("docgu_out", timeOut.getText().toString());
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
        printpdf=(ImageView) findViewById(R.id.printpdf);
        name = (TextView) findViewById(R.id.name_guest);
        company = (TextView) findViewById(R.id.company_guest);
        phone = (TextView) findViewById(R.id.phone_guest);
        division = (TextView) findViewById(R.id.division_guest);
        department = (TextView) findViewById(R.id.department_guest);
        pic = (TextView) findViewById(R.id.pic_guest);
        necessity = (TextView) findViewById(R.id.necessity_guest);
        date = (TextView) findViewById(R.id.date_guest);
        timeIn = (TextView) findViewById(R.id.timein_guest);
        timeOut = (TextView) findViewById(R.id.timeout_guest);
    }
}