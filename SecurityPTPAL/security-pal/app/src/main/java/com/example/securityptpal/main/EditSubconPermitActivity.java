package com.example.securityptpal.main;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.R;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionEmployee;
import com.example.securityptpal.model.Subcon;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Objects;

public class EditSubconPermitActivity extends AppCompatActivity {

    Spinner centerApprvSpinner, divApprvSpinner, divSpinner, depSpinner;
    private EditText company, phone, necessity, place, startDate, finishDate;
    private TextView division, department, divApproval, centerApproval;
    private Button save;
    String centerApprvItem, divApprvItem, divItem, depItem;
    int hour, minute;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> centerStatus, divStatus, divisionList, departmentList, statusList;
    Subcon subcon;
    private ProgressDialog progressDialog;
    private ArrayAdapter<String> centerSpinnerAdapter, divSpinnerAdapter, divisionAdapter, departmentAdapter;
    private List<Division> departments;
    FirebaseAuth mAuth;
    String userID;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_subcon_permit);
        mAuth = FirebaseAuth.getInstance();

        progressDialog = new ProgressDialog(EditSubconPermitActivity.this);
        company = findViewById(R.id.main_edit_subcon_permit_company);
        necessity = findViewById(R.id.main_edit_subcon_permit_necessity);
        divSpinner = findViewById(R.id.spinner_main_edit_subcon_permit_division);
        depSpinner = findViewById(R.id.spinner_main_edit_subcon_permit_depart);
        division = findViewById(R.id.main_edit_subcon_permit_division);
        department = findViewById(R.id.main_edit_subcon_permit_depart);
        divApproval = findViewById(R.id.subcon_edit_division_approval_status);
        centerApproval = findViewById(R.id.subcon_edit_center_approval);
        centerApprvSpinner = findViewById(R.id.main_edit_subcon_permit_status_center);
        divApprvSpinner = findViewById(R.id.main_edit_subcon_permit_status_div);
        save = findViewById(R.id.submit_edit_subcon);

        subcon = getIntent().getParcelableExtra("MAIN_EDIT_SUBCON_PERMIT");

        userID = mAuth.getCurrentUser().getUid();
        DocumentReference documentReference = db.collection("users").document(userID);
        documentReference.addSnapshotListener(EditSubconPermitActivity.this, new EventListener<DocumentSnapshot>() {
            @Override
            public void onEvent(@Nullable DocumentSnapshot value, @Nullable FirebaseFirestoreException error) {
                if (Objects.equals(value.getString("role"), "main") || Objects.equals(value.getString("role"), "division") || Objects.equals(value.getString("role"), "security")) {
                    centerApprvSpinner.setVisibility(View.VISIBLE);
                    divApprvSpinner.setVisibility(View.VISIBLE);

                    centerStatus = new ArrayList<>();
                    centerStatus.add("Accepted");
                    centerStatus.add("Pending");
                    centerStatus.add("Rejected");

                    centerSpinnerAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, centerStatus);
                    centerSpinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    centerApprvSpinner.setAdapter(centerSpinnerAdapter);
                    centerApprvSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                        @Override
                        public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                            centerApprvItem = centerApprvSpinner.getSelectedItem().toString();
                            centerApproval.setText(centerApprvItem);
                            if (centerApprvItem.equals("Pending")){
                                centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_orange_color));
                            } else if (centerApprvItem.equals("Accepted")){
                                centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_green_color));
                            } else {
                                centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.cardColorRed));
                            }
                        }

                        @Override
                        public void onNothingSelected(AdapterView<?> adapterView) {

                        }
                    });

                    divStatus = new ArrayList<>();
                    divStatus.add("Accepted");
                    divStatus.add("Pending");
                    divStatus.add("Rejected");

                    divSpinnerAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divStatus);
                    divSpinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    divApprvSpinner.setAdapter(divSpinnerAdapter);
                    divApprvSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                        @Override
                        public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                            divApprvItem = divApprvSpinner.getSelectedItem().toString();
                            divApproval.setText(divApprvItem);
                            if (divApprvItem.equals("Pending")){
                                divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_orange_color));
                            } else if (divApprvItem.equals("Accepted")){
                                divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_green_color));
                            } else {
                                divApproval.setTextColor(divApproval.getResources().getColor(R.color.cardColorRed));
                            }
                        }

                        @Override
                        public void onNothingSelected(AdapterView<?> adapterView) {

                        }
                    });

                } else {
                    centerApprvSpinner.setVisibility(View.GONE);
                    divApprvSpinner.setVisibility(View.GONE);
                }
            }
        });

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
                departmentAdapter = new ArrayAdapter<>(EditSubconPermitActivity.this,
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

        company.setText(subcon.getCompany());
        necessity.setText(subcon.getNecessity());
        division.setText(subcon.getDivision());
        department.setText(subcon.getDepartment());

        if (subcon.getDivision_approval().equals("Pending")){
            divApproval.setText(subcon.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_orange_color));
        } else if (subcon.getDivision_approval().equals("Accepted")){
            divApproval.setText(subcon.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_green_color));
        } else {
            divApproval.setText(subcon.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.cardColorRed));
        }

        if (subcon.getCenter_approval().equals("Pending")){
            centerApproval.setText(subcon.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_orange_color));
        } else if (subcon.getCenter_approval().equals("Accepted")){
            centerApproval.setText(subcon.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_green_color));
        } else {
            centerApproval.setText(subcon.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.cardColorRed));
        }

        save.setOnClickListener(view -> {
            db.collection("subcontractor").document(subcon.getId())
                    .update(
                            "company", company.getText().toString(),
                            "phone", phone.getText().toString(),
                            "necessity", necessity.getText().toString(),
                            "division", division.getText().toString(),
                            "department", department.getText().toString(),
                            "startDate", startDate.getText().toString(),
                            "finishDate", finishDate.getText().toString(),
                            "division_approval", divApproval.getText().toString(),
                            "center_approval", centerApproval.getText().toString()
                    )
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) {
                            StyleableToast.makeText(EditSubconPermitActivity.this, "Edit Data Successfully !!", Toast.LENGTH_SHORT,R.style.result).show();
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            StyleableToast.makeText(EditSubconPermitActivity.this, "Edit Data Failed !!" + e.getLocalizedMessage(), Toast.LENGTH_SHORT,R.style.resultfailed).show();
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
                Toast.makeText(EditSubconPermitActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}