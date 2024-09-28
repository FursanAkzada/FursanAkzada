package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.adapter.OnPermitLongClick;
import com.example.securityptpal.adapter.SubconAdapter;
import com.example.securityptpal.adapter.SubconEmployeeAdapter;
import com.example.securityptpal.main.EditSubconPermitActivity;
import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Subcon;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.google.firebase.storage.FirebaseStorage;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;
import java.util.List;

public class DetailSubconActivity extends AppCompatActivity implements OnPermitListener, OnPermitLongClick {
    TextView company, phone, necessity, division, department, startDate, finishDate;
    ProgressDialog progressDialog;
    Subcon subcon;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<EmployeeSubcon> list = new ArrayList<>();
    private SubconEmployeeAdapter subconEmployeeAdapter;
    private RecyclerView recyclerView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_subcon);

        progressDialog = new ProgressDialog(DetailSubconActivity.this);
        company = findViewById(R.id.detail_company_subcon);
//        phone = findViewById(R.id.detail_phone_subcon);
        necessity = findViewById(R.id.detail_necessity_subcon);
        division = findViewById(R.id.detail_division_subcon);
        department = findViewById(R.id.detail_department_subcon);
//        startDate = findViewById(R.id.detail_start_date_subcon);
//        finishDate = findViewById(R.id.detail_finish_date_subcon);
        recyclerView = findViewById(R.id.rv_employee_list);

        subcon = getIntent().getParcelableExtra("SUBCON_DETAIL");

        company.setText(subcon.getCompany());
        necessity.setText(subcon.getNecessity());
        division.setText(subcon.getDivision());
        department.setText(subcon.getDepartment());

        subconEmployeeAdapter = new SubconEmployeeAdapter(this, list, this, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(subconEmployeeAdapter);
        showAllData();
    }

    private void showAllData() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("subcontractor").document(subcon.getId()).collection("employee").addSnapshotListener(new EventListener<QuerySnapshot>() {
            @SuppressLint("NotifyDataSetChanged")
            @Override
            public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                list.clear();
                if (value != null) {
                    for (QueryDocumentSnapshot document : value) {
                        EmployeeSubcon employeeSubcon = new EmployeeSubcon(
                                document.getId(),
                                document.getString("periode"),
                                document.getString("name"),
                                document.getString("ttl"),
                                document.getString("phone"),
                                document.getString("position"),
                                document.getString("location"),
                                document.getString("start"),
                                document.getString("finish"),
                                document.getString("address"),
                                document.getString("img")
                        );
                        list.add(employeeSubcon);
                    }
                    subconEmployeeAdapter.notifyDataSetChanged();
                } else {
                    StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                }
                progressDialog.dismiss();
            }
        });
    }

    @Override
    public void onPermitClick(int position) {
        Intent intent = new Intent(DetailSubconActivity.this, DetailSubconEmployeeActivity.class);
        intent.putExtra("SUBCON_EMPLOYEE_DETAIL", list.get(position));
        intent.putExtra("SUBCON_DATA", subcon);
        startActivity(intent);
    }

    @Override
    public void onLongCLickListener(int pos) {
        final CharSequence[] dialogItem = {"Edit", "Delete"};
        AlertDialog.Builder dialog = new AlertDialog.Builder(DetailSubconActivity.this);
        dialog.setItems(dialogItem, new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                switch (i) {
                    case 0:
                        Intent intentEdit = new Intent(getApplicationContext(), EditEmployeeSubconActivity.class);
                        intentEdit.putExtra("EDIT_SUBCON_EMPLOYEE_PERMIT", list.get(pos));
                        intentEdit.putExtra("SUBCON_DATA_EDIT", subcon);
                        startActivity(intentEdit);
                        break;
                    case 1:
                        deleteData(list.get(pos).getId(), list.get(pos).getImg());
                        break;
                }
            }
        });
        dialog.show();
    }

    private void deleteData(String id, String urlImage) {
        progressDialog.setTitle("Loading");
        progressDialog.setMessage("Deleting data...");
        progressDialog.show();
        db.collection("subcontractor").document(subcon.getId()).collection("employee").document(id)
                .delete()
                .addOnCompleteListener(new OnCompleteListener<Void>() {
                    @Override
                    public void onComplete(@NonNull Task<Void> task) {
                        if (!task.isSuccessful()){
                            progressDialog.dismiss();
                            StyleableToast.makeText(getApplicationContext(), "Delete data Failed !!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                        } else {
                            FirebaseStorage.getInstance().getReferenceFromUrl(urlImage).delete().addOnCompleteListener(new OnCompleteListener<Void>() {
                                @Override
                                public void onComplete(@NonNull Task<Void> task) {
                                    StyleableToast.makeText(DetailSubconActivity.this, "Delete Data Successfull !!", Toast.LENGTH_SHORT, R.style.logsuccess).show();
                                    progressDialog.dismiss();
                                }
                            });
                        }
                    }
                });
    }
}