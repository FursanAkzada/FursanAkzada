package com.example.securityptpal.division;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.R;
import com.example.securityptpal.model.PermissionEmployee;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;

public class DetailExitActivity extends AppCompatActivity implements AdapterView.OnItemSelectedListener{

    Spinner spinner;
    private TextView base, name, nip, division, date, necessity, place, timeout, timeback, divApproval, centerApproval, department, status;
    private Button save;
    String item;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> divStatus;
    PermissionEmployee permissionEmployee;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_exit);

        base = findViewById(R.id.div_exit_permit_base);
        name = findViewById(R.id.div_exit_permit_name);
        nip = findViewById(R.id.div_exit_permit_nip);
        division = findViewById(R.id.div_exit_permit_division);
        date = findViewById(R.id.div_exit_permit_date);
        necessity = findViewById(R.id.div_exit_permit_necessity);
        place = findViewById(R.id.div_exit_permit_place);
        timeout = findViewById(R.id.div_exit_permit_timeout);
        timeback = findViewById(R.id.div_exit_permit_timeback);
        divApproval = findViewById(R.id.div_approval_status);
        centerApproval = findViewById(R.id.div_center_approval);
        spinner = findViewById(R.id.div_exit_permit_status_editable);
        save = findViewById(R.id.save_div_status);
        department = findViewById(R.id.div_exit_permit_depart);
        status = findViewById(R.id.div_exit_permit_statusE);

        divStatus = new ArrayList<>();
        divStatus.add("Accepted");
        divStatus.add("Pending");
        divStatus.add("Rejected");

        ArrayAdapter<String> adapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divStatus);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(adapter);
        spinner.setOnItemSelectedListener(this);

        permissionEmployee = getIntent().getParcelableExtra("EXIT_PERMIT");
        base.setText(permissionEmployee.getBase());
        name.setText(permissionEmployee.getName());
        nip.setText(permissionEmployee.getNip());
        division.setText(permissionEmployee.getDivision());
        date.setText(permissionEmployee.getDate());
        necessity.setText(permissionEmployee.getNecessity());
        place.setText(permissionEmployee.getPlace());
        timeout.setText(permissionEmployee.getTimeout());
        timeback.setText(permissionEmployee.getTimeback());
        department.setText(permissionEmployee.getDepartment());
        status.setText(permissionEmployee.getEmployee_status());

        if (permissionEmployee.getDivision_approval().equals("Pending")){
            divApproval.setText(permissionEmployee.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_orange_color));
        } else if (permissionEmployee.getDivision_approval().equals("Accepted")){
            divApproval.setText(permissionEmployee.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_green_color));
        } else {
            divApproval.setText(permissionEmployee.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.cardColorRed));
        }

        if (permissionEmployee.getCenter_approval().equals("Pending")){
            centerApproval.setText(permissionEmployee.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_orange_color));
        } else if (permissionEmployee.getCenter_approval().equals("Accepted")){
            centerApproval.setText(permissionEmployee.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_green_color));
        } else {
            centerApproval.setText(permissionEmployee.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.cardColorRed));
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
        divApproval.setText(item);
        if (item.equals("Pending")){
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_orange_color));
        } else if (item.equals("Accepted")){
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_green_color));
        } else {
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.cardColorRed));
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
            permissionEmployee.setDivision_approval(item);
            String id = permissionEmployee.getId();
            db.collection("permission_employee").document(id).set(permissionEmployee);
            StyleableToast.makeText(getApplicationContext(),"Change Status Successfull", Toast.LENGTH_SHORT,R.style.logsuccess).show();
        }
    }
}