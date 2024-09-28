package com.example.securityptpal.main;

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

import com.example.securityptpal.R;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionEmployee;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class EditExitPermitActivity extends AppCompatActivity {

    Spinner centerApprvSpinner, divApprvSpinner, divSpinner, depSpinner, statusSpinner;
    private EditText base, name, nip, date, necessity, place, timeout, timeback;
    private TextView division, department, employeeStatus, divApproval, centerApproval;
    private Button save;
    String centerApprvItem, divApprvItem, statusItem, divItem, depItem;
    int hour, minute;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> centerStatus, divStatus, divisionList, departmentList, statusList;
    PermissionEmployee permissionEmployee;
    private ProgressDialog progressDialog;
    private ArrayAdapter<String> centerSpinnerAdapter, divSpinnerAdapter, divisionAdapter, departmentAdapter, statusAdapter;
    private List<Division> departments;
    ImageView imgCalendar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_exit_permit);

        progressDialog = new ProgressDialog(EditExitPermitActivity.this);
        base = findViewById(R.id.main_edit_exit_permit_base);
        name = findViewById(R.id.main_edit_exit_permit_name);
        nip = findViewById(R.id.main_edit_exit_permit_nip);
        divSpinner = findViewById(R.id.spinner_main_edit_exit_permit_division);
        depSpinner = findViewById(R.id.spinner_main_edit_exit_permit_depart);
        statusSpinner = findViewById(R.id.spinner_main_edit_exit_permit_statusE);
        division = findViewById(R.id.main_edit_exit_permit_division);
        department = findViewById(R.id.main_edit_exit_permit_depart);
        employeeStatus = findViewById(R.id.main_edit_exit_permit_statusE);
        date = findViewById(R.id.main_edit_exit_permit_date);
        imgCalendar = findViewById(R.id.edit_calendar);
        necessity = findViewById(R.id.main_edit_exit_permit_necessity);
        place = findViewById(R.id.main_edit_exit_permit_place);
        timeout = findViewById(R.id.main_edit_exit_permit_timeout);
        timeback = findViewById(R.id.main_edit_exit_permit_timeback);
        divApproval = findViewById(R.id.main_edit_division_approval_status);
        centerApproval = findViewById(R.id.main_edit_center_approval);
        centerApprvSpinner = findViewById(R.id.main_edit_exit_permit_status_center);
        divApprvSpinner = findViewById(R.id.main_edit_exit_permit_status_div);
        save = findViewById(R.id.submit_edit_permit);

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

        permissionEmployee = getIntent().getParcelableExtra("MAIN_EDIT_EXIT_PERMIT");

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
                departmentAdapter = new ArrayAdapter<>(EditExitPermitActivity.this,
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
        statusAdapter = new ArrayAdapter<>(EditExitPermitActivity.this,
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

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        imgCalendar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        EditExitPermitActivity.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        calendar.set(year, month, day);

                        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd");

                        String dateString = dateFormat.format(calendar.getTime());
                        date.setText(dateString);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

        base.setText(permissionEmployee.getBase());
        name.setText(permissionEmployee.getName());
        nip.setText(permissionEmployee.getNip());
        division.setText(permissionEmployee.getDivision());
        department.setText(permissionEmployee.getDepartment());
        date.setText(permissionEmployee.getDate());
        necessity.setText(permissionEmployee.getNecessity());
        place.setText(permissionEmployee.getPlace());
        timeout.setText(permissionEmployee.getTimeout());
        timeback.setText(permissionEmployee.getTimeback());
        employeeStatus.setText(permissionEmployee.getEmployee_status());

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

        save.setOnClickListener(view -> {
            db.collection("permission_employee").document(permissionEmployee.getId())
                    .update(
                            "base", base.getText().toString(),
                            "name", name.getText().toString(),
                            "nip", nip.getText().toString(),
                            "division", division.getText().toString(),
                            "department", department.getText().toString(),
                            "employee_status", employeeStatus.getText().toString(),
                            "date", date.getText().toString(),
                            "necessity", necessity.getText().toString(),
                            "place", place.getText().toString(),
                            "timeout", timeout.getText().toString(),
                            "timeback", timeback.getText().toString(),
                            "division_approval", divApproval.getText().toString(),
                            "center_approval", centerApproval.getText().toString()
                    )
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) {
                            StyleableToast.makeText(EditExitPermitActivity.this, "Edit Data Successfully !!", Toast.LENGTH_SHORT, R.style.logsuccess).show();
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            StyleableToast.makeText(EditExitPermitActivity.this, "Edit Data Failed !!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                        }
                    });
            finish();
        });
    }

    public void pop(View view)
    {
        TimePickerDialog.OnTimeSetListener onTimeSetListener = new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker timePicker, int selectedHour, int selectedMinute) {
                hour = selectedHour;
                minute = selectedMinute;
                timeout.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
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
                timeback.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
            }
        };

        TimePickerDialog timePickerDialog = new TimePickerDialog(this,onTimeSetListener,hour,minute,true);

        timePickerDialog.setTitle("Select Time");
        timePickerDialog.show();
    }

    private void getDivision() {
        progressDialog.setTitle("Loading");
        progressDialog.setMessage("Menyimpan...");
        progressDialog.show();
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
                Toast.makeText(EditExitPermitActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}