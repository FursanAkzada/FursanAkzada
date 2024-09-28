package com.example.securityptpal.main;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.example.securityptpal.R;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionLate;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;
import java.util.List;

public class EditLatePermitActivity extends AppCompatActivity {

    Spinner divSpinner, depSpinner, statusSpinner;
    private EditText name, nip, reason;
    private TextView division, department, employeeStatus, date, device, latitude, longitude, location;
    private Button save;
    String centerApprvItem, divApprvItem, statusItem, divItem, depItem;
    int hour, minute;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> centerStatus, divStatus, divisionList, departmentList, statusList;
    PermissionLate permissionLate;
    private ProgressDialog progressDialog;
    private ArrayAdapter<String> divisionAdapter, departmentAdapter, statusAdapter;
    private List<Division> departments;
    private ImageView imgEvidence;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_late_permit);

        progressDialog = new ProgressDialog(EditLatePermitActivity.this);
        name = findViewById(R.id.edit_main_late_name);
        nip = findViewById(R.id.edit_main_late_nip);
        divSpinner = findViewById(R.id.spinner_main_edit_late_division);
        depSpinner = findViewById(R.id.spinner_main_edit_late_department);
        statusSpinner = findViewById(R.id.spinner_main_edit_late_status);
        division = findViewById(R.id.edit_main_late_division);
        department = findViewById(R.id.edit_main_late_department);
        employeeStatus = findViewById(R.id.edit_main_late_status);
        reason = findViewById(R.id.edit_main_late_reason);
        imgEvidence = findViewById(R.id.edit_main_image_late);
        date = findViewById(R.id.edit_main_late_date);
        device = findViewById(R.id.edit_main_late_device);
        latitude = findViewById(R.id.edit_main_late_latitude);
        longitude = findViewById(R.id.edit_main_late_longitude);
        location = findViewById(R.id.edit_main_late_location);
        save = findViewById(R.id.submit_edit_late_permit);

        permissionLate = getIntent().getParcelableExtra("MAIN_EDIT_LATE_PERMIT");
        getDivision();
        divisionList = new ArrayList<>();
        divisionAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divisionList);
        divisionAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        divSpinner.setAdapter(divisionAdapter);

        departmentList = new ArrayList<>();
        divSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                divItem = divSpinner.getSelectedItem().toString();
                division.setText(divItem);
                departmentList.clear();
                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                departmentAdapter = new ArrayAdapter<>(EditLatePermitActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                depSpinner.setAdapter(departmentAdapter);
                depSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                        depItem = depSpinner.getSelectedItem().toString();
                        department.setText(depItem);
                    }

                    @Override
                    public void onNothingSelected(AdapterView<?> adapterView) {

                    }
                });
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        statusList = new ArrayList<>();
        statusList.add("PKWT");
        statusList.add("PKWTT");
        statusAdapter = new ArrayAdapter<>(EditLatePermitActivity.this,
                android.R.layout.simple_spinner_dropdown_item, statusList);
        statusSpinner.setAdapter(statusAdapter);
        statusSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                statusItem = statusSpinner.getSelectedItem().toString();
                employeeStatus.setText(statusItem);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        name.setText(permissionLate.getName());
        nip.setText(permissionLate.getNip());
        division.setText(permissionLate.getDivision());
        department.setText(permissionLate.getDepartment());
        employeeStatus.setText(permissionLate.getEmployee_status());
        reason.setText(permissionLate.getReason());
        date.setText(permissionLate.getDate());
        device.setText(permissionLate.getDevice());
        latitude.setText(permissionLate.getLatitude());
        longitude.setText(permissionLate.getLongitude());
        location.setText(permissionLate.getLocation());
        Glide.with(this).load(permissionLate.getImg()).placeholder(R.drawable.pict).into(imgEvidence);

        save.setOnClickListener(view -> {
            db.collection("permission_late").document(permissionLate.getId())
                    .update(
                            "name", name.getText().toString(),
                            "nip", nip.getText().toString(),
                            "division", division.getText().toString(),
                            "department", department.getText().toString(),
                            "employee_status", employeeStatus.getText().toString(),
                            "reason", reason.getText().toString()
                    )
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) {
                            StyleableToast.makeText(getApplicationContext(),"Data Edited Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            StyleableToast.makeText(getApplicationContext(),"Data Failed to Edit!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                    });
            finish();
        });
    }

    private void getDivision() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog2);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("division").get().addOnSuccessListener(new OnSuccessListener<QuerySnapshot>() {
            @Override
            public void onSuccess(QuerySnapshot queryDocumentSnapshots) {
                progressDialog.hide();
                departments = queryDocumentSnapshots.toObjects(Division.class);
                if (queryDocumentSnapshots.size()>0) {
                    divisionList.clear();
                    for (DocumentSnapshot doc : queryDocumentSnapshots) {
                        divisionList.add(doc.getString("name"));
                    }
                    divisionAdapter.notifyDataSetChanged();
                }
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                progressDialog.hide();
                Toast.makeText(EditLatePermitActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}