package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.widget.Toast;

import com.example.securityptpal.adapter.CheckupAdapter;
import com.example.securityptpal.adapter.GuestAdapter;
import com.example.securityptpal.model.CheckUp;
import com.example.securityptpal.model.Guest;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;
import java.util.List;

public class MonitoringCheckUp extends AppCompatActivity implements CheckupAdapter.OnCheckupListener {
    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<CheckUp> list = new ArrayList<>();
    private CheckupAdapter checkupAdapter;
    private SearchView searchView;
    private ProgressDialog progressDialog;
    private Intent intent;
    SwipeRefreshLayout mSwipeRefreshLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_monitoring_check_up);
        recyclerView = findViewById(R.id.rv_monitoring_checkup);
        progressDialog = new ProgressDialog(MonitoringCheckUp.this);
        mSwipeRefreshLayout = findViewById(R.id.refresh_checkup);
        searchView = findViewById(R.id.search_checkup);
        searchView.clearFocus();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchData(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
//                searchData(newText);
                return false;
            }
        });
        mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
//                showAllData();
                mSwipeRefreshLayout.setRefreshing(false);
            }
        });

        checkupAdapter = new CheckupAdapter(getApplicationContext(), list, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(getApplicationContext(), LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(getApplicationContext(), DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(checkupAdapter);
//        showAllData();
    }

    private void searchData(String nip) {
        progressDialog = new ProgressDialog(MonitoringCheckUp.this);
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog2);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("permission_checkup")
                .whereEqualTo("nip", nip)
                .orderBy("date", Query.Direction.DESCENDING)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                CheckUp checkup = new CheckUp(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("nip"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("status"),
                                        document.getString("date"),
                                        document.getString("type"),
                                        document.getString("others"),
                                        document.getString("division_approval"),
                                        document.getString("center_approval")
                                );
                                list.add(checkup);
                            }
                            checkupAdapter.notifyDataSetChanged();
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

//    private void showAllData(){
//        progressDialog.show();
//        progressDialog.setContentView(R.layout.progress_dialog2);
//        progressDialog.getWindow().setBackgroundDrawableResource(
//                android.R.color.transparent
//        );
//        db.collection("permission_checkup")
//                .orderBy("date", Query.Direction.DESCENDING)
//                .get()
//                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
//                    @SuppressLint("NotifyDataSetChanged")
//                    @Override
//                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
//                        list.clear();
//                        if (task.isSuccessful()) {
//                            for (QueryDocumentSnapshot document : task.getResult()) {
//                                CheckUp checkup = new CheckUp(
//                                        document.getId(),
//                                        document.getString("name"),
//                                        document.getString("nip"),
//                                        document.getString("division"),
//                                        document.getString("department"),
//                                        document.getString("status"),
//                                        document.getString("date"),
//                                        document.getString("type"),
//                                        document.getString("others"),
//                                        document.getString("division_approval"),
//                                        document.getString("center_approval")
//                                );
//                                list.add(checkup);
//                            }
//                            checkupAdapter.notifyDataSetChanged();
//                            progressDialog.dismiss();
//                        } else {
//                            Toast.makeText(MonitoringCheckUp.this, "data gagal dimuat", Toast.LENGTH_SHORT).show();
//                            progressDialog.dismiss();
//                        }
//                    }
//                })
//                .addOnFailureListener(new OnFailureListener() {
//                    @Override
//                    public void onFailure(@NonNull Exception e) {
//                        Toast.makeText(MonitoringCheckUp.this, "data tidak ditemukan", Toast.LENGTH_SHORT).show();
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
    public void onCheckupClick(int position) {
        intent = new Intent(MonitoringCheckUp.this, DetailCheckupAct.class);
        intent.putExtra("permission_cu", list.get(position));
        startActivity(intent);
    }

    @Override
    protected void onResume() {
        super.onResume();
//        showAllData();
    }
}