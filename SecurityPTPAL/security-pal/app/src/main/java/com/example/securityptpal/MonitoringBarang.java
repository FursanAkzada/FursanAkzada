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

import com.example.securityptpal.adapter.GoodsAdapter;
import com.example.securityptpal.adapter.MainGoodsPermitAdapter;
import com.example.securityptpal.adapter.MonitoringGoodsPermitAdapter;
import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.model.Barang;
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

public class MonitoringBarang extends AppCompatActivity implements GoodsAdapter.OnGoodsListener {

    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<Barang> list = new ArrayList<>();
    private GoodsAdapter goodsAdapter;
    private ProgressDialog progressDialog;
    private SearchView searchView;
    SwipeRefreshLayout mSwipeRefreshLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_monitoring_barang);
        recyclerView = findViewById(R.id.rv_monitoring_goods);
        searchView = findViewById(R.id.search_goods);
        progressDialog = new ProgressDialog(MonitoringBarang.this);
        mSwipeRefreshLayout = findViewById(R.id.refresh_monitoring_goods_permit);

        goodsAdapter = new GoodsAdapter(this, list, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(goodsAdapter);

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

        mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
//                showAllDataDesc();
                mSwipeRefreshLayout.setRefreshing(false);
            }
        });
    }

    private void searchData(String name) {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("goods_permit")
                .whereEqualTo("goods_name", name)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                Barang barang = new Barang(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("phone"),
                                        document.getString("pic"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("goods_name"),
                                        document.getString("type"),
                                        document.getString("img"),
                                        document.getString("date"),
                                        document.getString("device"),
                                        document.getString("latitude"),
                                        document.getString("longitude"),
                                        document.getString("location"),
                                        document.getString("status")
                                );
                                list.add(barang);
                                progressDialog.dismiss();
                            }
                            goodsAdapter.notifyDataSetChanged();
                        } else {
                            StyleableToast.makeText(getApplicationContext(), "Data Failed to Load", Toast.LENGTH_SHORT, R.style.result).show();
                            progressDialog.dismiss();
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

//    private void showAllDataDesc() {
//        progressDialog.show();
//        progressDialog.setContentView(R.layout.progress_dialog1);
//        progressDialog.getWindow().setBackgroundDrawableResource(
//                android.R.color.transparent
//        );
//        db.collection("goods_permit")
//                .orderBy("date", Query.Direction.DESCENDING)
//                .get()
//                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
//                    @SuppressLint("NotifyDataSetChanged")
//                    @Override
//                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
//                        list.clear();
//                        if (task.isSuccessful()) {
//                            for (QueryDocumentSnapshot document : task.getResult()) {
//                                Barang barang = new Barang(
//                                        document.getId(),
//                                        document.getString("name"),
//                                        document.getString("phone"),
//                                        document.getString("pic"),
//                                        document.getString("division"),
//                                        document.getString("department"),
//                                        document.getString("goods_name"),
//                                        document.getString("type"),
//                                        document.getString("img"),
//                                        document.getString("date"),
//                                        document.getString("device"),
//                                        document.getString("latitude"),
//                                        document.getString("longitude"),
//                                        document.getString("location"),
//                                        document.getString("status")
//                                );
//                                list.add(barang);
//                            }
//                            monitoringGoodsPermitAdapter.notifyDataSetChanged();
//                        } else {
//                            StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
//                        }
//                        progressDialog.dismiss();
//                    }
//                })
//                .addOnFailureListener(new OnFailureListener() {
//                    @Override
//                    public void onFailure(@NonNull Exception e) {
//                        Toast.makeText(MonitoringBarang.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
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
    protected void onResume() {
        super.onResume();
//        showAllDataDesc();
    }

    @Override
    public void onGoodsClick(int position) {
        Intent intent = new Intent(MonitoringBarang.this, DetailBarangActivity.class);
        intent.putExtra("MAIN_GOODS_PERMIT", list.get(position));
        startActivity(intent);
    }
}