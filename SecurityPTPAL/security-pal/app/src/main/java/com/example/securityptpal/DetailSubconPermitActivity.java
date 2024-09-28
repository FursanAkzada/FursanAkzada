package com.example.securityptpal;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.adapter.OnPermitLongClick;
import com.example.securityptpal.adapter.SubconEmployeeAdapter;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.Subcon;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;
import java.util.List;

public class DetailSubconPermitActivity extends AppCompatActivity implements OnPermitListener, AdapterView.OnItemSelectedListener, OnPermitLongClick {

    private TextView company, phone, necessity, division, department, startDate, finishDate, division_approve, center_approve;
    ProgressDialog progressDialog;
    Subcon subcon;
    Spinner spinner;
    private Button save;
    String item;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> divStatus;
    ImageView permit_ttd;
    private List<EmployeeSubcon> list = new ArrayList<>();
    private SubconEmployeeAdapter subconEmployeeAdapter;
    private RecyclerView recyclerView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_subcon_permit);
        progressDialog = new ProgressDialog(DetailSubconPermitActivity.this);
        company = findViewById(R.id.detail_company_subcon);
        necessity = findViewById(R.id.detail_necessity_subcon);
        division = findViewById(R.id.detail_division_subcon);
        department = findViewById(R.id.detail_department_subcon);
        division_approve = findViewById(R.id.main_division_approval_status);
        center_approve = findViewById(R.id.main_center_approval);
        spinner = findViewById(R.id.main_guest_status_editable);
        save = findViewById(R.id.save_center_status);
        permit_ttd = findViewById(R.id.permit_ttd);
        recyclerView = findViewById(R.id.rv_employee_list);

        divStatus = new ArrayList<>();
        divStatus.add("Accepted");
        divStatus.add("Pending");
        divStatus.add("Rejected");

        ArrayAdapter<String> adapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divStatus);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(adapter);
        spinner.setOnItemSelectedListener(this);

        subconEmployeeAdapter = new SubconEmployeeAdapter(this, list, this,this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(subconEmployeeAdapter);

        subcon = getIntent().getParcelableExtra("MAIN_SUBCON_PERMIT");
        company.setText(subcon.getCompany());
        necessity.setText(subcon.getNecessity());
        division.setText(subcon.getDivision());
        department.setText(subcon.getDepartment());

        if (subcon.getDivision_approval().equals("Pending")){
            division_approve.setText(subcon.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
        } else if (subcon.getDivision_approval().equals("Accepted")){
            division_approve.setText(subcon.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
        } else {
            division_approve.setText(subcon.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (subcon.getCenter_approval().equals("Pending")){
            center_approve.setText(subcon.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
        } else if (subcon.getCenter_approval().equals("Accepted")){
            center_approve.setText(subcon.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
        } else {
            center_approve.setText(subcon.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (subcon.getCenter_approval().equals("Accepted") && subcon.getDivision_approval().equals("Accepted")){
            permit_ttd.setImageResource(R.drawable.ttdhcm);
        }

        save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                saveValue(item);
            }
        });
        showAllData();
    }

    @Override
    public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
        item = spinner.getSelectedItem().toString();
        division_approve.setText(item);
        if (item.equals("Pending")){
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
        } else if (item.equals("Accepted")){
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
        } else {
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
        }
    }

    private void saveValue(String item) {
        if (item.equals("Change Status")) {
            StyleableToast.makeText(getApplicationContext(),"Please select a status", Toast.LENGTH_SHORT,R.style.warning).show();
        }
        else {
            subcon.setDivision_approval(item);
            String id = subcon.getId();
            db.collection("subcontractor").document(id).set(subcon);
            StyleableToast.makeText(getApplicationContext(),"Change Status Successfull", Toast.LENGTH_SHORT,R.style.logsuccess).show();
        }
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
        Intent intent = new Intent(DetailSubconPermitActivity.this, DetailSubconEmployeeActivity.class);
        intent.putExtra("SUBCON_EMPLOYEE_DETAIL", list.get(position));
        intent.putExtra("SUBCON_DATA", subcon);
        startActivity(intent);
    }

    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {

    }

    @Override
    public void onLongCLickListener(int pos) {

    }
}