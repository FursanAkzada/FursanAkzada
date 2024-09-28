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

import com.example.securityptpal.model.CheckUp;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;

public class DetailCheckupActivity extends AppCompatActivity implements AdapterView.OnItemSelectedListener{

    private TextView name, nip,division, department, status, date, type, others,division_approve, center_approve;
    Spinner spinner;
    private Button save;
    String item;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> centerStatus;
    CheckUp checkUp;
    ImageView permit_ttd;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_checkup2);
        name = findViewById(R.id.name_cu);
        nip = findViewById(R.id.nip_cu);
        division = findViewById(R.id.division_cu);
        department = findViewById(R.id.depart_cu);
        status = findViewById(R.id.status_cu);
        date = findViewById(R.id.date_cu);
        type = findViewById(R.id.type_cu);
        others = findViewById(R.id.others_cu);
        division_approve = findViewById(R.id.main_division_approval_status);
        center_approve = findViewById(R.id.main_center_approval);
        spinner = findViewById(R.id.main_checkup_status_editable);
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

        checkUp = getIntent().getParcelableExtra("MAIN_CHECKUP_PERMIT");
        name.setText(checkUp.getName());
        nip.setText(checkUp.getNip());
        division.setText(checkUp.getDivision());
        department.setText(checkUp.getDepartment());
        status.setText(checkUp.getStatus());
        date.setText(checkUp.getDate());
        type.setText(checkUp.getType());
        others.setText(checkUp.getOthers());

        if (checkUp.getDivision_approval().equals("Pending")){
            division_approve.setText(checkUp.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
        } else if (checkUp.getDivision_approval().equals("Accepted")){
            division_approve.setText(checkUp.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
        } else {
            division_approve.setText(checkUp.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (checkUp.getCenter_approval().equals("Pending")){
            center_approve.setText(checkUp.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
        } else if (checkUp.getCenter_approval().equals("Accepted")){
            center_approve.setText(checkUp.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
        } else {
            center_approve.setText(checkUp.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (checkUp.getCenter_approval().equals("Accepted") && checkUp.getDivision_approval().equals("Accepted")){
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
            checkUp.setDivision_approval(item);
            String id = checkUp.getId();
            db.collection("permission_checkup").document(id).set(checkUp);
            StyleableToast.makeText(getApplicationContext(),"Change Status Successfull", Toast.LENGTH_SHORT,R.style.logsuccess).show();
        }
    }
}