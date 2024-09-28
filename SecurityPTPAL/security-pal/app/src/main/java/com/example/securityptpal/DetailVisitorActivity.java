package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.model.Visitor;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;

public class DetailVisitorActivity extends AppCompatActivity implements AdapterView.OnItemSelectedListener {
    private TextView name, company, phone, division, department, pic, necessity, date, timein, timeout, division_approve, center_approve;
    Spinner spinner;
    private Button save;
    String item;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> centerStatus;
    Visitor visitor;
    ImageView permit_ttd;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_visitor2);

        name = findViewById(R.id.name);
        company = findViewById(R.id.company);
        phone = findViewById(R.id.phone);
        division = findViewById(R.id.division);
        department = findViewById(R.id.department);
        pic = findViewById(R.id.pic);
        necessity = findViewById(R.id.necessity);
        date = findViewById(R.id.date);
        timein = findViewById(R.id.timein);
        timeout = findViewById(R.id.timeout);
        division_approve = findViewById(R.id.main_division_approval_status);
        center_approve = findViewById(R.id.main_center_approval);
        spinner = findViewById(R.id.main_visitor_status_editable);
        save = findViewById(R.id.save_center_status);
        permit_ttd = findViewById(R.id.permit_ttd);

        centerStatus = new ArrayList<>();
        centerStatus.add("Accepted");
        centerStatus.add("Pending");
        centerStatus.add("Rejected");

        ArrayAdapter<String> adapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, centerStatus);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(adapter);
        spinner.setOnItemSelectedListener(this);

        visitor = getIntent().getParcelableExtra("MAIN_VISITOR_PERMIT");
        name.setText(visitor.getName());
        company.setText(visitor.getCompany());
        phone.setText(visitor.getPhone());
        division.setText(visitor.getDivision());
        department.setText(visitor.getDepartment());
        pic.setText(visitor.getPic());
        necessity.setText(visitor.getNecessity());
        date.setText(visitor.getDate());
        timein.setText(visitor.getTimein());
        timeout.setText(visitor.getTimeout());

        if (visitor.getDivision_approval().equals("Pending")){
            division_approve.setText(visitor.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
        } else if (visitor.getDivision_approval().equals("Accepted")){
            division_approve.setText(visitor.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
        } else {
            division_approve.setText(visitor.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (visitor.getCenter_approval().equals("Pending")){
            center_approve.setText(visitor.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
        } else if (visitor.getCenter_approval().equals("Accepted")){
            center_approve.setText(visitor.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
        } else {
            center_approve.setText(visitor.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (visitor.getCenter_approval().equals("Accepted") && visitor.getDivision_approval().equals("Accepted")){
            permit_ttd.setImageResource(R.drawable.ttdhcm);
        }

        save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                saveValue(item);
            }
        });
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

    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {

    }

    private void saveValue(String item) {
        if (item.equals("Change Status")) {
            StyleableToast.makeText(getApplicationContext(),"Please select a status", Toast.LENGTH_SHORT,R.style.warning).show();
        }
        else {
            visitor.setDivision_approval(item);
            String id = visitor.getId();
            db.collection("permission_visitor").document(id).set(visitor);
            StyleableToast.makeText(getApplicationContext(),"Change Status Successfull", Toast.LENGTH_SHORT,R.style.logsuccess).show();
        }
    }
}