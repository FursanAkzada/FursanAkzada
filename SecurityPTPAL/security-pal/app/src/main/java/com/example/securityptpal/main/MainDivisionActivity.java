package com.example.securityptpal.main;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.example.securityptpal.EditCheckupPermitActivity;
import com.example.securityptpal.R;
import com.example.securityptpal.UtamaDataBarang;
import com.example.securityptpal.UtamaDataCheckup;
import com.example.securityptpal.UtamaDataGuest;
import com.example.securityptpal.UtamaDataParksub;
import com.example.securityptpal.UtamaDataSubcon;
import com.example.securityptpal.UtamaDataVisitor;
import com.example.securityptpal.Utama_Data_Cometoolate;
import com.example.securityptpal.VisitorPermissionActivity;
import com.example.securityptpal.adapter.DivisionAdapter;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.Visitor;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.FieldValue;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;

import java.util.ArrayList;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class MainDivisionActivity extends AppCompatActivity implements DivisionAdapter.OnDivisionListener {

    private RecyclerView recyclerView;
    private SearchView searchView;
    private List<Division> list = new ArrayList<>();
    private DivisionAdapter divisionAdapter;
    private ProgressDialog progressDialog;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    FloatingActionButton fab;
    AlertDialog dialog;
    DrawerLayout drawerLayout;
    ImageView btMenu, btnFilter;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_division);
        recyclerView = findViewById(R.id.rv_main_division);
        searchView = findViewById(R.id.main_search_division);
        fab = findViewById(R.id.fab_add_division);
        drawerLayout = findViewById(R.id.drawer_layout);
        btMenu = findViewById(R.id.bt_menu);

        btMenu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                drawerLayout.openDrawer(GravityCompat.START);
            }
        });
        progressDialog = new ProgressDialog(MainDivisionActivity.this);

        divisionAdapter = new DivisionAdapter(this, list, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(divisionAdapter);

        fab.setOnClickListener(view -> {
            showAddDialog();
        });

        divisionAdapter.setDialog(new DivisionAdapter.Dialog() {
            @Override
            public void onClick(int pos) {
                final CharSequence[] dialogItem = {"Add Department", "Edit", "Delete"};
                AlertDialog.Builder builder = new AlertDialog.Builder(MainDivisionActivity.this);
                View layout = getLayoutInflater().inflate(R.layout.edit_divisi, null);
                Button btnEdit = layout.findViewById(R.id.btn_edt);
                Button btnDelete = layout.findViewById(R.id.btn_dlt);
                Button btnAdd = layout.findViewById(R.id.btn_add);

                btnEdit.setOnClickListener(view1 -> {
                    editData(list, pos);
                });
                btnDelete.setOnClickListener(view1 -> {
                    new SweetAlertDialog(MainDivisionActivity.this, SweetAlertDialog.WARNING_TYPE)
                            .setTitleText("Warning!!!")
                            .setContentText("Are you sure want to delete this data ?")
                            .setConfirmText("OK")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    try {
                                        deleteData(list.get(pos).getId());
                                        sDialog.dismissWithAnimation();
                                        StyleableToast.makeText(getApplicationContext(), "Delete Successfully!!!", Toast.LENGTH_SHORT, R.style.result).show();
                                    } catch (Exception e) {
                                        Log.e("error", e.getMessage());
                                    }
                                }
                            })
                            .setCancelButton("CANCEL", new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    sDialog.dismissWithAnimation();
                                }
                            })
                            .show();
                    dialog.dismiss();
                });
                btnAdd.setOnClickListener(view -> {
                    addDepartment(list.get(pos).getId());
                });
                builder.setView(layout);
                dialog = builder.create();
                dialog.show();
            }
        });
    }

    private void showAllData() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("division")
                .orderBy("name", Query.Direction.ASCENDING)
                .get()
                .addOnSuccessListener(new OnSuccessListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onSuccess(QuerySnapshot queryDocumentSnapshots) {
                        list.clear();
                        for (QueryDocumentSnapshot documentSnapshot : queryDocumentSnapshots) {
                            Division mDivision = documentSnapshot.toObject(Division.class);

                            Division division = new Division(
                                    documentSnapshot.getId(),
                                    documentSnapshot.getString("name"),
                                    mDivision.getDepartment()
                            );
                            list.add(division);
                        }
                        divisionAdapter.notifyDataSetChanged();
                        progressDialog.dismiss();
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(MainDivisionActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        progressDialog.dismiss();
                    }
                });
    }

    private void deleteData(String id){
        db.collection("division").document(id)
                .delete()
                .addOnCompleteListener(new OnCompleteListener<Void>() {
                    @Override
                    public void onComplete(@NonNull Task<Void> task) {
                        if (!task.isSuccessful()){
                            StyleableToast.makeText(getApplicationContext(), "Delete Data is Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                        progressDialog.dismiss();
                        onStart();
                    }
                });
    }

    private void editData(List<Division> divisions, int pos) {
        AlertDialog.Builder builder = new AlertDialog.Builder(MainDivisionActivity.this);
        builder.setTitle("Edit Division");
        View view = getLayoutInflater().inflate(R.layout.editor_division_dialog, null);

        EditText edtName = view.findViewById(R.id.name_division);
        Button btnSubmit = view.findViewById(R.id.btn_add_division);

        edtName.setText(divisions.get(pos).getName());

        btnSubmit.setOnClickListener(view1 -> {
            progressDialog.show();
            progressDialog.setContentView(R.layout.progress_dialog);
            progressDialog.getWindow().setBackgroundDrawableResource(
                    android.R.color.transparent
            );
            db.collection("division").document(divisions.get(pos).getId())
                    .update("name", edtName.getText().toString())
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) {
                            StyleableToast.makeText(getApplicationContext(),"Data Edited Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                            onStart();
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            StyleableToast.makeText(getApplicationContext(),"Data Failed to Edit!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                    });
            dialog.dismiss();
            onStart();
        });
        builder.setView(view);
        dialog = builder.create();
        dialog.show();
    }

    private void addDepartment(String id){
        AlertDialog.Builder builder = new AlertDialog.Builder(MainDivisionActivity.this);
        builder.setTitle("Add Department");
        View view = getLayoutInflater().inflate(R.layout.editor_division_dialog, null);

        EditText edtName = view.findViewById(R.id.name_division);
        Button btnSubmit = view.findViewById(R.id.btn_add_division);

        btnSubmit.setOnClickListener(view1 -> {
            try{
                if (TextUtils.isEmpty(edtName.getText().toString())){
                    edtName.setError("Fill the name!");
                }else{
                    db.collection("division").document(id)
                            .update("department", FieldValue.arrayUnion(edtName.getText().toString()))
                            .addOnSuccessListener(new OnSuccessListener<Void>() {
                                @Override
                                public void onSuccess(Void unused) {
                                    StyleableToast.makeText(getApplicationContext(),"Data Send Sucessfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                    progressDialog.dismiss();
                                }
                            })
                            .addOnFailureListener(new OnFailureListener() {
                                @Override
                                public void onFailure(@NonNull Exception e) {
                                    StyleableToast.makeText(MainDivisionActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT,R.style.resultfailed).show();
                                    progressDialog.dismiss();
                                }
                            });
                    dialog.dismiss();
                    onStart();
                }
            }
            catch (Exception e){
                e.printStackTrace();
            }
        });

        builder.setView(view);
        dialog = builder.create();
        dialog.show();
    }

    private void showAddDialog(){
        AlertDialog.Builder builder = new AlertDialog.Builder(MainDivisionActivity.this);
        builder.setTitle("Add Division");
        View view = getLayoutInflater().inflate(R.layout.editor_division_dialog, null);

        EditText edtName = view.findViewById(R.id.name_division);
        Button btnSubmit = view.findViewById(R.id.btn_add_division);

        btnSubmit.setOnClickListener(view1 -> {
            try{
                if (TextUtils.isEmpty(edtName.getText().toString())){
                    edtName.setError("Fill the name!");
                }else{
                    saveData(
                            db.collection("division").document().getId(),
                            edtName.getText().toString()
                    );
                    dialog.dismiss();
                    onStart();
                }
            }
            catch (Exception e){
                e.printStackTrace();
            }
        });

        builder.setView(view);
        dialog = builder.create();
        dialog.show();
    }

    private void saveData(String id, String name) {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );

        List<String> department = new ArrayList<>();

        Division division = new Division(
                id,
                name,
                department
        );

        db.collection("division").add(division)
                .addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
                    @Override
                    public void onSuccess(DocumentReference documentReference) {
                        StyleableToast.makeText(getApplicationContext(),"Data Send Sucessfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                        progressDialog.dismiss();
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(MainDivisionActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        progressDialog.dismiss();
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
        AkunUtama.redirectActivity(this, AkunUtama.class);
    }

    public void ClickEmployee(View view){
        AkunUtama.redirectActivity(this, UtamaDataEmployee.class);
        finish();
    }

    public void ClickComelate(View view){
        AkunUtama.redirectActivity(this, Utama_Data_Cometoolate.class);
    }

    public void ClickCheckup(View view){ AkunUtama.redirectActivity(this, UtamaDataCheckup.class); }

    public void ClickSubcon(View view){ AkunUtama.redirectActivity(this, UtamaDataSubcon.class); }

    public void ClickParksub(View view){ AkunUtama.redirectActivity(this, UtamaDataParksub.class); }

    public void ClickGuest(View view){
        AkunUtama.redirectActivity(this, UtamaDataGuest.class);
    }

    public void ClickVisitor(View view){
        AkunUtama.redirectActivity(this, UtamaDataVisitor.class);
    }

    public void ClickGoods(View view){
        AkunUtama.redirectActivity(this, UtamaDataBarang.class);
    }

    public void ClickEdit(View view){
        AkunUtama.redirectActivity(this, MainDivisionActivity.class);
    }

    public void ClickExit(View view){
        AkunUtama.exit(this);
    }

    public static void redirectActivity(Activity activity, Class aClass){
        Intent intent = new Intent(activity,aClass);

        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

        activity.startActivity(intent);
    }

    @Override
    protected void onStart() {
        super.onStart();
        showAllData();
    }

    @Override
    protected void onResume() {
        super.onResume();
        showAllData();
    }

    @Override
    public void onDivisionClick(int position) {
        Intent intent = new Intent(MainDivisionActivity.this, DetailDivisionActivity.class);
        intent.putExtra("division", list.get(position));
        startActivity(intent);
    }
}