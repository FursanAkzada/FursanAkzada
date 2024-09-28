package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
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
import android.widget.TimePicker;
import android.widget.Toast;

import com.example.securityptpal.model.CheckUp;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.Visitor;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class EditVisitorPermitActivity extends AppCompatActivity {
    Button submit;
    Spinner spinner, spinner2, centerApprvSpinner, divApprvSpinner;
    ProgressDialog progressDialog;
    EditText edtNameVisitor, edtCompanyVisitor, edtNoHPVisitor, edtPICVisitor, edtNecessityVisitor, edtDateVisitor, edtTimeoutVisitor, edtTimeinVisitor;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    ImageView dateVisitor, img_timeoutVisitor, img_timeinVisitor;
    int hour, minute;
    ArrayList<String> divisionList, departmentList, centerStatus, divStatus;
    private ArrayAdapter divSpinnerAdapter, depSpinnerAdapter, centerSpinnerAdapter, diviSpinnerAdapter;
    private List<Division> departments;
    private TextView txtDiv, txtDep, divApproval, centerApproval;
    Visitor visitor;
    String divItem, depItem, centerApprvItem, divApprvItem;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_visitor_permit);

        submit = findViewById(R.id.edit_submit_visitor);
        spinner = findViewById(R.id.edit_visitor_div_spinner);
        spinner2 = findViewById(R.id.edit_visitor_dep_spinner);
        txtDep = findViewById(R.id.edit_department_visitor);
        edtNameVisitor = findViewById(R.id.edtNameVisitor);
        edtCompanyVisitor = findViewById(R.id.edtCompanyVisitor);
        edtNoHPVisitor = findViewById(R.id.edtNoHPVisitor);
        txtDiv = findViewById(R.id.edit_division_visitor);
        edtPICVisitor = findViewById(R.id.edtPICVisitor);
        edtNecessityVisitor = findViewById(R.id.edtNecessityVisitor);
        edtDateVisitor = findViewById(R.id.edtDateVisitor);
        edtTimeoutVisitor = findViewById(R.id.edtTimeoutVisitor);
        edtTimeinVisitor = findViewById(R.id.edtTimeinVisitor);
        dateVisitor = findViewById(R.id.dateVisitor);
        img_timeoutVisitor = findViewById(R.id.img_timeoutVisitor);
        img_timeinVisitor = findViewById(R.id.img_timeinVisitor);
        divApproval = findViewById(R.id.main_vis_division_approval_status);
        centerApproval = findViewById(R.id.main_edit_center_approval);
        centerApprvSpinner = findViewById(R.id.main_edit_vis_permit_status_center);
        divApprvSpinner = findViewById(R.id.main_edit_vis_permit_status_div);

        progressDialog = new ProgressDialog(EditVisitorPermitActivity.this);

        visitor = getIntent().getParcelableExtra("MAIN_EDIT_VISITOR_PERMIT");
        edtDateVisitor.setText(visitor.getDate());
        edtNameVisitor.setText(visitor.getName());
        edtCompanyVisitor.setText(visitor.getCompany());
        edtPICVisitor.setText(visitor.getPic());
        edtNoHPVisitor.setText(visitor.getPhone());
        edtNecessityVisitor.setText(visitor.getNecessity());
        edtTimeinVisitor.setText(visitor.getTimein());
        edtTimeoutVisitor.setText(visitor.getTimeout());
        txtDiv.setText(visitor.getDivision());
        txtDep.setText(visitor.getDepartment());

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        getDivision();
        divisionList = new ArrayList<>();
        divSpinnerAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divisionList);
        divSpinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(divSpinnerAdapter);

        departmentList = new ArrayList<>();
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                divItem = spinner.getSelectedItem().toString();
                txtDiv.setText(divItem);
                departmentList.clear();
                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                depSpinnerAdapter = new ArrayAdapter<>(EditVisitorPermitActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                spinner2.setAdapter(depSpinnerAdapter);
                spinner2.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                        depItem = spinner2.getSelectedItem().toString();
                        txtDep.setText(depItem);
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
        dateVisitor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        EditVisitorPermitActivity.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        month = month + 1;
                        String date = day+"-"+month+"-"+year;
                        edtDateVisitor.setText(date);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

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

        diviSpinnerAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divStatus);
        diviSpinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        divApprvSpinner.setAdapter(diviSpinnerAdapter);
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

        if (visitor.getDivision_approval().equals("Pending")){
            divApproval.setText(visitor.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_orange_color));
        } else if (visitor.getDivision_approval().equals("Accepted")){
            divApproval.setText(visitor.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.main_green_color));
        } else {
            divApproval.setText(visitor.getDivision_approval());
            divApproval.setTextColor(divApproval.getResources().getColor(R.color.cardColorRed));
        }

        if (visitor.getCenter_approval().equals("Pending")){
            centerApproval.setText(visitor.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_orange_color));
        } else if (visitor.getCenter_approval().equals("Accepted")){
            centerApproval.setText(visitor.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.main_green_color));
        } else {
            centerApproval.setText(visitor.getCenter_approval());
            centerApproval.setTextColor(centerApproval.getResources().getColor(R.color.cardColorRed));
        }

        submit.setOnClickListener(view -> {
            saveData();
        });
    }

    private void saveData() {
        db.collection("permission_visitor").document(visitor.getId())
                .update(
                        "name", edtNameVisitor.getText().toString(),
                        "company", edtCompanyVisitor.getText().toString(),
                        "phone", edtNoHPVisitor.getText().toString(),
                        "division", txtDiv.getText().toString(),
                        "department", txtDep.getText().toString(),
                        "pic", edtPICVisitor.getText().toString(),
                        "necessity", edtNecessityVisitor.getText().toString(),
                        "date", edtDateVisitor.getText().toString(),
                        "timein", edtTimeinVisitor.getText().toString(),
                        "timeout", edtTimeoutVisitor.getText().toString(),
                        "division_approval", divApproval.getText().toString(),
                        "center_approval", centerApproval.getText().toString()
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
                departments = queryDocumentSnapshots.toObjects(Division.class);
                progressDialog.hide();
                if (queryDocumentSnapshots.size()>0) {
                    divisionList.clear();
                    for (DocumentSnapshot doc : queryDocumentSnapshots) {
                        divisionList.add(doc.getString("name"));
                    }
                    divSpinnerAdapter.notifyDataSetChanged();
                }
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                progressDialog.hide();
                Toast.makeText(EditVisitorPermitActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    public void pop(View view)
    {
        TimePickerDialog.OnTimeSetListener onTimeSetListener = new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker timePicker, int selectedHour, int selectedMinute) {
                hour = selectedHour;
                minute = selectedMinute;
                edtTimeinVisitor.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
            }
        };

        TimePickerDialog timePickerDialog = new TimePickerDialog(this,onTimeSetListener,hour,minute,true);

        timePickerDialog.setTitle("Select Time");
        timePickerDialog.show();
    }

    public void pop2(View view)
    {
        TimePickerDialog.OnTimeSetListener onTimeSetListener = new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker timePicker, int selectedHour, int selectedMinute) {
                hour = selectedHour;
                minute = selectedMinute;
                edtTimeoutVisitor.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
            }
        };

        TimePickerDialog timePickerDialog = new TimePickerDialog(this,onTimeSetListener,hour,minute,true);

        timePickerDialog.setTitle("Select Time");
        timePickerDialog.show();
    }
}