package com.example.securityptpal.employee;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.text.TextUtils;
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

import com.example.securityptpal.CheckUpPermissionActivity;
import com.example.securityptpal.LogoutAccount;
import com.example.securityptpal.R;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionEmployee;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class Employee extends AppCompatActivity {
    ImageView Calendar, img_timeout, img_timeback, imgSignOut;
    EditText edtBase, edtName, edtNip, edtDate, edtNecessity, edtPlace, edtTimeout, edtTimeback;
    TextView txtDepart;
    DatePickerDialog.OnDateSetListener setListener;
    Spinner divSpinner, depSpinner, statusSpinner;
    int hour, minute;
    String base, name, nip, division, date, necessity, place, timeout, timeback, status, department;
    FirebaseFirestore db;
    Button btnSendEmployee, btnMonitoring;
    private ArrayAdapter divSpinnerAdapter, depSpinnerAdapter, statusSpinnerAdapter;
    private ProgressDialog progressDialog;
    ArrayList<String> divisionList, departmentList, statusList;
    private List<Division> departments;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_employee);

//        txtDepart = findViewById(R.id.txtDepart);
        Calendar = findViewById(R.id.calendar);
        edtDate = (EditText) findViewById(R.id.edtDate);
        img_timeout = findViewById(R.id.img_timeout);
        img_timeback = findViewById(R.id.img_timeback);
        imgSignOut = findViewById(R.id.sign_out_permission);
        edtTimeout = (EditText) findViewById(R.id.edtTimeout);
        edtTimeback = (EditText) findViewById(R.id.edtTimeback);
        edtBase = (EditText) findViewById(R.id.edtBase);
        edtName = (EditText) findViewById(R.id.edtName);
        edtNip = (EditText) findViewById(R.id.edtNip);
        edtNecessity = (EditText) findViewById(R.id.edtNecessity);
        edtPlace = (EditText) findViewById(R.id.edtPlace);
        btnSendEmployee = findViewById(R.id.btn_send_employee);
        btnMonitoring = findViewById(R.id.gotoMonitoring);
        divSpinner = findViewById(R.id.spinner_division_employee);
        depSpinner = findViewById(R.id.spinner_department_employee);
        statusSpinner = findViewById(R.id.spinner_status_employee);
        progressDialog = new ProgressDialog(Employee.this);
        progressDialog.setTitle("Loading");
        progressDialog.setMessage("Menyimpan...");
        db = FirebaseFirestore.getInstance();

        getDivision();
        divisionList = new ArrayList<>();
        statusList = new ArrayList<>();
        departmentList = new ArrayList<>();
        statusList.add("PKWT");
        statusList.add("PKWTT");
        divSpinnerAdapter = new ArrayAdapter<>(Employee.this,
                android.R.layout.simple_spinner_dropdown_item, divisionList);
        divSpinner.setAdapter(divSpinnerAdapter);
        divSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                departmentList.clear();
                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                depSpinnerAdapter = new ArrayAdapter<>(Employee.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                depSpinner.setAdapter(depSpinnerAdapter);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        statusSpinnerAdapter = new ArrayAdapter<>(Employee.this,
                android.R.layout.simple_spinner_dropdown_item, statusList);
        statusSpinner.setAdapter(statusSpinnerAdapter);

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        Calendar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        Employee.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        month = month + 1;
                        String date = year+"/"+month+"/"+day;
                        edtDate.setText(date);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

        btnSendEmployee.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                try{
                    if (TextUtils.isEmpty(edtBase.getText().toString()) || TextUtils.isEmpty(edtName.getText().toString()) || TextUtils.isEmpty(edtNip.getText().toString()) || TextUtils.isEmpty(edtDate.getText().toString()) || TextUtils.isEmpty(edtNecessity.getText().toString()) || TextUtils.isEmpty(edtPlace.getText().toString()) || TextUtils.isEmpty(edtTimeout.getText().toString()) || TextUtils.isEmpty(edtTimeback.getText().toString())){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                        Alerter.create(Employee.this)
                                .setTitle("Add Data Failed!")
                                .setText("Please fill all the data")
                                .setIcon(R.drawable.ic_close)
                                .setBackgroundColorRes(android.R.color.holo_red_dark)
                                .setDuration(2000)
                                .enableSwipeToDismiss()
                                .enableProgress(true)
                                .setProgressColorRes(R.color.design_default_color_primary)
                                .setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View view) {

                                    }
                                })
                                .setOnShowListener(new OnShowAlertListener() {
                                    @Override
                                    public void onShow() {

                                    }
                                })
                                .setOnHideListener(new OnHideAlertListener() {
                                    @Override
                                    public void onHide() {

                                    }
                                })
                                .show();
                    }else{
                        base = edtBase.getText().toString();
                        name = edtName.getText().toString();
                        nip = edtNip.getText().toString();
                        date = edtDate.getText().toString();
                        necessity = edtNecessity.getText().toString();
                        place = edtPlace.getText().toString();
                        timeout = edtTimeout.getText().toString();
                        timeback = edtTimeback.getText().toString();
                        division = divSpinner.getSelectedItem().toString();
                        status = statusSpinner.getSelectedItem().toString();
                        department = depSpinner.getSelectedItem().toString();

                        progressDialog = new ProgressDialog(Employee.this);
                        progressDialog.show();
                        progressDialog.setContentView(R.layout.progress_dialog);
                        progressDialog.getWindow().setBackgroundDrawableResource(
                                android.R.color.transparent
                        );
                        Thread timer = new Thread(){
                            @Override
                            public void run() {
                                try {
                                    sleep(2000);
                                    Intent intent = new Intent(getApplicationContext(),Employee.class);
                                    startActivity(intent);
                                    progressDialog.dismiss();
                                    finish();
                                    super.run();
                                }catch (InterruptedException e){
                                    e.printStackTrace();
                                }
                            }
                        };
                        timer.start();
                        new Handler().postDelayed(new Runnable() {
                            @Override
                            public void run() {
                                //do something
                                StyleableToast.makeText(getApplicationContext(),"Data Send Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                            }
                        }, 2000 );
                        PermissionEmployee permissionEmployee = new PermissionEmployee(
                                db.collection("permission_employee").document().getId(),
                                base,
                                name,
                                nip,
                                division,
                                date,
                                necessity,
                                place,
                                timeout,
                                timeback,
                                "Pending",
                                "Pending",
                                status,
                                department
                        );
                        db.collection("permission_employee").add(permissionEmployee).addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
                            @Override
                            public void onSuccess(DocumentReference documentReference) {
                                //StyleableToast.makeText(getApplicationContext(),"Data Send Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                            }
                        }).addOnFailureListener(new OnFailureListener() {
                            @Override
                            public void onFailure(@NonNull Exception e) {
                                StyleableToast.makeText(getApplicationContext(),"Data Send Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                                progressDialog.dismiss();
                            }
                        });
                    }
                }
                catch (Exception e){
                    e.printStackTrace();
                }
            }
        });

        btnMonitoring.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(Employee.this, MonitoringEmployee.class));
            }
        });

        imgSignOut.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                LogoutAccount.logout(Employee.this);
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
                edtTimeout.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
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
                edtTimeback.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
            }
        };

        TimePickerDialog timePickerDialog = new TimePickerDialog(this,onTimeSetListener,hour,minute,true);

        timePickerDialog.setTitle("Select Time");
        timePickerDialog.show();
    }

    private void getDivision() {
        progressDialog = new ProgressDialog(Employee.this);
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
                    divSpinnerAdapter.notifyDataSetChanged();
                }
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                progressDialog.hide();
                Toast.makeText(Employee.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}