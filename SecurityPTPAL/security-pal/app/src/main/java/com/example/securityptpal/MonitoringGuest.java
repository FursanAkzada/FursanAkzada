package com.example.securityptpal;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.core.app.ActivityCompat;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.adapter.GuestAdapter;
import com.example.securityptpal.adapter.LatePermissionAdapter;
import com.example.securityptpal.adapter.OnPermitLongClick;
import com.example.securityptpal.main.EditSubconPermitActivity;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.MemberGuest;
import com.example.securityptpal.model.PermissionLate;
import com.example.securityptpal.model.Subcon;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.google.firebase.storage.StorageReference;
import com.google.firebase.storage.UploadTask;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class MonitoringGuest extends AppCompatActivity implements GuestAdapter.OnGuestListener, OnPermitLongClick {

    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<Guest> list = new ArrayList<>();
    private GuestAdapter guestAdapter;
    private SearchView searchView;
    private ProgressDialog progressDialog;
    private Intent intent;
    AlertDialog dialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_monitoring_guest);
        recyclerView = findViewById(R.id.rv_monitoring_guest);
        progressDialog = new ProgressDialog(MonitoringGuest.this);
        searchView = findViewById(R.id.search_guest);
        searchView.clearFocus();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchData(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                //searchData(newText);

                return false;
            }
        });

        guestAdapter = new GuestAdapter(getApplicationContext(), list, this, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(getApplicationContext(), LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(getApplicationContext(), DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(guestAdapter);
//        showAllData();
    }

    private void searchData(String name) {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("permission_guest")
                .whereEqualTo("name", name)
                .orderBy("date", Query.Direction.DESCENDING)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                Guest guest = new Guest(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("company"),
                                        document.getString("phone"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("pic"),
                                        document.getString("necessity"),
                                        document.getString("date"),
                                        document.getString("timeIn"),
                                        document.getString("timeOut"),
                                        document.getString("division_approval"),
                                        document.getString("center_approval")
                                );
                                list.add(guest);
                            }
                            guestAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            StyleableToast.makeText(getApplicationContext(), "Data Failed to Load", Toast.LENGTH_SHORT, R.style.result).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(getApplicationContext(), "Data Not Found!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                    }
                });
    }

    @Override
    public void onLongCLickListener(int pos) {
        final CharSequence[] dialogItem = {"Add Members"};
        AlertDialog.Builder builder = new AlertDialog.Builder(MonitoringGuest.this);
        View layout = getLayoutInflater().inflate(R.layout.edit_guest, null);
        Button btnAdd = layout.findViewById(R.id.btn_add);
        btnAdd.setOnClickListener(view -> {
            addEmployee(list, pos);
        });
        builder.setView(layout);
        dialog = builder.create();
        dialog.show();
    }

    private void addEmployee(List<Guest> guests, int pos){
        AlertDialog.Builder builder = new AlertDialog.Builder(MonitoringGuest.this);
        builder.setTitle("Add Members");
        View view = getLayoutInflater().inflate(R.layout.add_member_guest, null);

        EditText edtName = (EditText) view.findViewById(R.id.name_member);
        Button btnSubmit = view.findViewById(R.id.btn_add_member);

        btnSubmit.setOnClickListener(view1 -> {
            if (TextUtils.isEmpty(edtName.getText().toString())){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                Alerter.create(MonitoringGuest.this)
                        .setTitle("Add Data Failed!")
                        .setText("Please fill all the data")
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
            } else {
                progressDialog.setTitle("Loading");
                progressDialog.setMessage("Sending data...");
                progressDialog.show();
                MemberGuest memberGuest = new MemberGuest(
                        db.collection("permission_guest").document(guests.get(pos).getId()).collection("members").document().getId(),
                        edtName.getText().toString()
                );
                db.collection("permission_guest")
                        .document(guests.get(pos).getId())
                        .collection("members")
                        .add(memberGuest)
                        .addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
                            @Override
                            public void onSuccess(DocumentReference documentReference) {
                                StyleableToast.makeText(MonitoringGuest.this, "Success adding member", Toast.LENGTH_SHORT, R.style.logsuccess).show();
                            }
                        }).addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(MonitoringGuest.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT, R.style.resultfailed).show();
                    }
                });
                dialog.dismiss();
                progressDialog.dismiss();
            }
        });
        builder.setView(view);
        dialog = builder.create();
        dialog.show();
    }

//    private void showAllData(){
//        progressDialog.show();
//        progressDialog.setContentView(R.layout.progress_dialog2);
//        progressDialog.getWindow().setBackgroundDrawableResource(
//                android.R.color.transparent
//        );
//        db.collection("permission_guest")
//                .orderBy("date", Query.Direction.DESCENDING)
//                .get()
//                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
//                    @SuppressLint("NotifyDataSetChanged")
//                    @Override
//                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
//                        list.clear();
//                        if (task.isSuccessful()) {
//                            for (QueryDocumentSnapshot document : task.getResult()) {
//                                Guest guest = new Guest(
//                                        document.getId(),
//                                        document.getString("name"),
//                                        document.getString("company"),
//                                        document.getString("phone"),
//                                        document.getString("division"),
//                                        document.getString("department"),
//                                        document.getString("pic"),
//                                        document.getString("necessity"),
//                                        document.getString("date"),
//                                        document.getString("timeIn"),
//                                        document.getString("timeOut"),
//                                        document.getString("division_approval"),
//                                        document.getString("center_approval")
//                                );
//                                list.add(guest);
//                            }
//                            guestAdapter.notifyDataSetChanged();
//                            progressDialog.dismiss();
//                        } else {
//                            Toast.makeText(MonitoringGuest.this, "data gagal dimuat", Toast.LENGTH_SHORT).show();
//                            progressDialog.dismiss();
//                        }
//                    }
//                })
//                .addOnFailureListener(new OnFailureListener() {
//                    @Override
//                    public void onFailure(@NonNull Exception e) {
//                        Toast.makeText(MonitoringGuest.this, "data tidak ditemukan", Toast.LENGTH_SHORT).show();
//                        progressDialog.dismiss();
//                    }
//                });
//    }


    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }

    @Override
    public void onGuestClick(int position) {
        intent = new Intent(MonitoringGuest.this, DetailGuestAct.class);
        intent.putExtra("permission_gue", list.get(position));
        startActivity(intent);
    }
}