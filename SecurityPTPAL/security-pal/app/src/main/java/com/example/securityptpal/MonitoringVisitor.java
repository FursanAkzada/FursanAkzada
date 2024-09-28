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

import com.example.securityptpal.adapter.VisitorAdapter;
import com.example.securityptpal.employee.DetailPermissionActivity;
import com.example.securityptpal.employee.MonitoringEmployee;
import com.example.securityptpal.model.Visitor;
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

public class MonitoringVisitor extends AppCompatActivity implements VisitorAdapter.OnVisitorListener{
    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<Visitor> list = new ArrayList<>();
    private VisitorAdapter visitorAdapter;
    private SearchView searchView;
    private ProgressDialog progressDialog;
    private Intent intent;
    SwipeRefreshLayout mSwipeRefreshLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_monitoring_visitor);
        recyclerView = findViewById(R.id.rv_monitoring_visitor);
        progressDialog = new ProgressDialog(MonitoringVisitor.this);
        mSwipeRefreshLayout = findViewById(R.id.refresh_visitor);
        searchView = findViewById(R.id.search_visitor);
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

        visitorAdapter = new VisitorAdapter(getApplicationContext(), list, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(getApplicationContext(), LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(getApplicationContext(), DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(visitorAdapter);
//        showAllData();
    }

    private void searchData(String name) {
        progressDialog = new ProgressDialog(MonitoringVisitor.this);
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("permission_visitor")
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
                                Visitor visitor = new Visitor(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("company"),
                                        document.getString("phone"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("pic"),
                                        document.getString("necessity"),
                                        document.getString("date"),
                                        document.getString("timein"),
                                        document.getString("timeout"),
                                        document.getString("division_approval"),
                                        document.getString("center_approval")
                                );
                                list.add(visitor);
                            }
                            visitorAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            Toast.makeText(MonitoringVisitor.this, "data gagal dimuat", Toast.LENGTH_SHORT).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(MonitoringVisitor.this, "data tidak ditemukan", Toast.LENGTH_SHORT).show();
                        progressDialog.hide();
                    }
                });
    }

//    private void showAllData(){
//        progressDialog.show();
//        progressDialog.setContentView(R.layout.progress_dialog2);
//        progressDialog.getWindow().setBackgroundDrawableResource(
//                android.R.color.transparent
//        );
//        db.collection("permission_visitor")
//                .orderBy("date", Query.Direction.DESCENDING)
//                .get()
//                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
//                    @SuppressLint("NotifyDataSetChanged")
//                    @Override
//                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
//                        list.clear();
//                        if (task.isSuccessful()) {
//                            for (QueryDocumentSnapshot document : task.getResult()) {
//                                Visitor visitor = new Visitor(
//                                        document.getId(),
//                                        document.getString("name"),
//                                        document.getString("company"),
//                                        document.getString("phone"),
//                                        document.getString("division"),
//                                        document.getString("department"),
//                                        document.getString("pic"),
//                                        document.getString("necessity"),
//                                        document.getString("date"),
//                                        document.getString("timein"),
//                                        document.getString("timeout"),
//                                        document.getString("division_approval"),
//                                        document.getString("center_approval")
//                                );
//                                list.add(visitor);
//                            }
//                            visitorAdapter.notifyDataSetChanged();
//                            progressDialog.dismiss();
//                        } else {
//                            StyleableToast.makeText(getApplicationContext(), "Data Failed to Load", Toast.LENGTH_SHORT, R.style.result).show();
//                            progressDialog.hide();
//                        }
//                    }
//                })
//                .addOnFailureListener(new OnFailureListener() {
//                    @Override
//                    public void onFailure(@NonNull Exception e) {
//                        StyleableToast.makeText(getApplicationContext(), "Data Not Found!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
//                    }
//                });
//    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startActivity(new Intent(this, VisitorPermissionActivity.class));
        finish();
    }

    @Override
    public void onVisitorClick(int position) {
        intent = new Intent(MonitoringVisitor.this, DetailVisitorAct.class);
        intent.putExtra("permission", list.get(position));
        startActivity(intent);
    }

    @Override
    protected void onResume() {
        super.onResume();
//        showAllData();
    }
}