package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.widget.Toast;

import com.example.securityptpal.adapter.LatePermissionAdapter;
import com.example.securityptpal.adapter.PermissionEmployeeAdapter;
import com.example.securityptpal.employee.MonitoringEmployee;
import com.example.securityptpal.model.PermissionEmployee;
import com.example.securityptpal.model.PermissionLate;
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

public class MonitoringComeTooLate extends AppCompatActivity implements LatePermissionAdapter.OnLateListener {

    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<PermissionLate> list = new ArrayList<>();
    private LatePermissionAdapter latePermissionAdapter;
    private SearchView searchView;
    private Intent intent;
    private ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_monitoring_come_too_late);
        recyclerView = findViewById(R.id.rv_monitoring_late);
        progressDialog = new ProgressDialog(MonitoringComeTooLate.this);
        searchView = findViewById(R.id.search_late);
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

        latePermissionAdapter = new LatePermissionAdapter(getApplicationContext(), list, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(getApplicationContext(), LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(getApplicationContext(), DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(latePermissionAdapter);

    }

    private void searchData(String nip) {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("permission_late")
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
                                PermissionLate permissionLate = new PermissionLate(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("nip"),
                                        document.getString("division"),
                                        document.getString("reason"),
                                        document.getString("img"),
                                        document.getString("date"),
                                        document.getString("device"),
                                        document.getString("latitude"),
                                        document.getString("longitude"),
                                        document.getString("location"),
                                        document.getString("employee_status"),
                                        document.getString("department")
                                );
                                list.add(permissionLate);
                            }
                            latePermissionAdapter.notifyDataSetChanged();
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
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }

    @Override
    public void onLateClick(int position) {
        intent = new Intent(MonitoringComeTooLate.this, UtamaCometoolate.class);
        intent.putExtra("MAIN_LATE_PERMIT", list.get(position));
        startActivity(intent);
    }
}