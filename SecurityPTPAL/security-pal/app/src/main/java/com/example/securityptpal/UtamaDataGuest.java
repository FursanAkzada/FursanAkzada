package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Toast;

import com.example.securityptpal.adapter.MainGoodsPermitAdapter;
import com.example.securityptpal.adapter.MainGuestAdapter;
import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.adapter.OnPermitLongClick;
import com.example.securityptpal.main.AkunUtama;
import com.example.securityptpal.main.EditGoodsPermitActivity;
import com.example.securityptpal.main.EditGuestPermitActivity;
import com.example.securityptpal.main.MainDivisionActivity;
import com.example.securityptpal.main.UtamaDataEmployee;
import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.Visitor;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class UtamaDataGuest extends AppCompatActivity implements OnPermitListener, OnPermitLongClick {

    DrawerLayout drawerLayout;
    ImageView btMenu, btnFilter;
    RecyclerView recyclerView;
    SearchView searchView;
    SwipeRefreshLayout mSwipeRefreshLayout;
    private ProgressDialog progressDialog;
    private MainGuestAdapter mainGuestAdapter;
    private List<Guest> list = new ArrayList<>();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    AlertDialog dialog;
    int filterCode = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_utama_data_guest);

        recyclerView = findViewById(R.id.rv_main_guest_permit);
        searchView = findViewById(R.id.main_search_guest);
        mSwipeRefreshLayout = findViewById(R.id.refresh_main_guest_permit);
        progressDialog = new ProgressDialog(UtamaDataGuest.this);
        drawerLayout = findViewById(R.id.drawer_layout);
        btnFilter = findViewById(R.id.main_filter_guest);
        btMenu = findViewById(R.id.bt_menu);

        btMenu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                drawerLayout.openDrawer(GravityCompat.START);
            }
        });

        searchView.clearFocus();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchData(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                return false;
            }
        });

        mainGuestAdapter = new MainGuestAdapter(this, list, this, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(mainGuestAdapter);
        mainGuestAdapter.setDialog(new MainGuestAdapter.Dialog() {
            @Override
            public void onClick(int pos) {
                final CharSequence[] dialogItem = {"Edit", "Delete"};
                AlertDialog.Builder builder = new AlertDialog.Builder(UtamaDataGuest.this);
                View layout = getLayoutInflater().inflate(R.layout.edit_delete, null);
                Button btnEdit = layout.findViewById(R.id.btn_edt);
                Button btnDelete = layout.findViewById(R.id.btn_dlt);

                btnEdit.setOnClickListener(view1 -> {
                    Intent intentEdit = new Intent(getApplicationContext(), EditGuestPermitActivity.class);
                    intentEdit.putExtra("MAIN_EDIT_GUEST_PERMIT", list.get(pos));
                    startActivity(intentEdit);
                    dialog.dismiss();
                });
                btnDelete.setOnClickListener(view1 -> {
                    new SweetAlertDialog(UtamaDataGuest.this, SweetAlertDialog.WARNING_TYPE)
                            .setTitleText("Warning!!!")
                            .setContentText("Are you sure want to delete this data ?")
                            .setConfirmText("OK")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    try{
                                        deleteData(list.get(pos).getId());
                                        sDialog.dismissWithAnimation();
                                        StyleableToast.makeText(getApplicationContext(), "Delete Successfully!!!", Toast.LENGTH_SHORT, R.style.result).show();
                                    } catch (Exception e) {
                                        Log.e("error",e.getMessage());
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
                builder.setView(layout);
                dialog = builder.create();
                dialog.show();
            }
        });

        showAllData();
        mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                showAllData();
                mSwipeRefreshLayout.setRefreshing(false);
            }
        });

        btnFilter.setOnClickListener(view -> {
            AlertDialog.Builder builder = new AlertDialog.Builder(UtamaDataGuest.this);
            View layout = getLayoutInflater().inflate(R.layout.filter_dialog, null);
            Button btnAscending = layout.findViewById(R.id.btn_asc);
            Button btnDescending = layout.findViewById(R.id.btn_desc);

            btnDescending.setOnClickListener(view1 -> {
                filterCode = 1;
                filter(filterCode);
                dialog.dismiss();
            });
            btnAscending.setOnClickListener(view1 -> {
                filterCode = 0;
                filter(filterCode);
                dialog.dismiss();
            });
            builder.setView(layout);
            dialog = builder.create();
            dialog.show();
        });
    }

    private void filter(int code) {
        if (code == 0) {
            showAllDataDesc();
        } else if (code == 1) {
            showAllDataAsc();
        }
    }

    private void showAllDataDesc() {
        db.collection("permission_guest")
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
                            mainGuestAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            Toast.makeText(UtamaDataGuest.this, "data gagal dimuat", Toast.LENGTH_SHORT).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(UtamaDataGuest.this, "data tidak ditemukan", Toast.LENGTH_SHORT).show();
                        progressDialog.hide();
                    }
                });
    }

    private void showAllDataAsc() {
        db.collection("permission_guest")
                .orderBy("date", Query.Direction.ASCENDING)
                .addSnapshotListener(new EventListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                        list.clear();
                        if (value != null) {
                            for (QueryDocumentSnapshot  document : value) {
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
                            mainGuestAdapter.notifyDataSetChanged();
                        } else {
                            StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                        progressDialog.dismiss();
                    }
                });
    }

    private void deleteData(String id) {
        db.collection("permission_guest").document(id)
                .delete()
                .addOnCompleteListener(new OnCompleteListener<Void>() {
                    @Override
                    public void onComplete(@NonNull Task<Void> task) {
                        if (!task.isSuccessful()){
                            Toast.makeText(getApplicationContext(), "Data gagal di hapus!", Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(getApplicationContext(), "Data berhasil di hapus!", Toast.LENGTH_SHORT).show();
                        }
                        progressDialog.dismiss();
                        showAllData();
                    }
                });
    }

    private void showAllData(){
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog2);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("permission_guest")
                .orderBy("date", Query.Direction.DESCENDING)
                .addSnapshotListener(new EventListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                        list.clear();
                        if (value != null) {
                            for (QueryDocumentSnapshot  document : value) {
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
                            mainGuestAdapter.notifyDataSetChanged();
                        } else {
                            StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                        progressDialog.dismiss();
                    }
                });
    }

    private void searchData(String name) {
        progressDialog.setTitle("Loading");
        progressDialog.setMessage("Fetching Data...");
        progressDialog.show();
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
                            mainGuestAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            Toast.makeText(UtamaDataGuest.this, "data gagal dimuat", Toast.LENGTH_SHORT).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(UtamaDataGuest.this, "data tidak ditemukan", Toast.LENGTH_SHORT).show();
                        progressDialog.hide();
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

    public void ClickParksub(View view){ redirectActivity(this, UtamaDataParksub.class); }

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
    public void onPermitClick(int position) {
        Intent intent = new Intent(UtamaDataGuest.this, DetailGuestPermitActivity.class);
        intent.putExtra("MAIN_GUEST_PERMIT", list.get(position));
        startActivity(intent);
    }

    @Override
    public void onLongCLickListener(int pos) {
        final CharSequence[] dialogItem = {"Edit", "Delete"};
        AlertDialog.Builder builder = new AlertDialog.Builder(UtamaDataGuest.this);
        View layout = getLayoutInflater().inflate(R.layout.edit_delete, null);
        Button btnEdit = layout.findViewById(R.id.btn_edt);
        Button btnDelete = layout.findViewById(R.id.btn_dlt);

        btnEdit.setOnClickListener(view1 -> {
            Intent intentEdit = new Intent(getApplicationContext(), EditGuestPermitActivity.class);
            intentEdit.putExtra("MAIN_EDIT_GUEST_PERMIT", list.get(pos));
            startActivity(intentEdit);
            dialog.dismiss();
        });
        btnDelete.setOnClickListener(view1 -> {
            new SweetAlertDialog(UtamaDataGuest.this, SweetAlertDialog.WARNING_TYPE)
                    .setTitleText("Warning!!!")
                    .setContentText("Are you sure want to delete this data ?")
                    .setConfirmText("OK")
                    .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                        @Override
                        public void onClick(SweetAlertDialog sDialog) {
                            try{
                                deleteData(list.get(pos).getId());
                                sDialog.dismissWithAnimation();
                                StyleableToast.makeText(getApplicationContext(), "Delete Successfully!!!", Toast.LENGTH_SHORT, R.style.result).show();
                            } catch (Exception e) {
                                Log.e("error",e.getMessage());
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
        builder.setView(layout);
        dialog = builder.create();
        dialog.show();
    }
}