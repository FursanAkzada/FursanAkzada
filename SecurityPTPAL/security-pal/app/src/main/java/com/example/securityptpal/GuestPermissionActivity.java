package com.example.securityptpal;

import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TimePicker;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.Guest;
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

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class GuestPermissionActivity extends AppCompatActivity {
    ImageView calGuest, img_timeout, img_timein;
    EditText edtDate, edtTimeout, edtTimein, edtName, edtCompany, edtPhone, edtPic, edtNecessity;
    String name, company, phone, division, department, pic, necessity, date, timeIn, timeOut;
    Button monitoring, submit;
    DatePickerDialog.OnDateSetListener setListener;
    int hour, minute;
    ArrayList<String> divisionList, departmentList;
    Spinner divSpinner, depSpinner;
    private ProgressDialog progressDialog;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayAdapter divSpinnerAdapter, depSpinnerAdapter;
    private List<Division> departments;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_guest_permission);

        calGuest = findViewById(R.id.calGuest);
        edtDate = findViewById(R.id.edtDateGuest);
        edtName = findViewById(R.id.edtNameGuest);
        edtCompany = findViewById(R.id.edtCompanyGuest);
        edtPhone = findViewById(R.id.edtNoHPGuest);
        edtPic = findViewById(R.id.edtPICGuest);
        edtNecessity = findViewById(R.id.edtNecessityGuest);
        img_timeout = findViewById(R.id.img_guest_timeout);
        img_timein = findViewById(R.id.img_guest_timein);
        edtTimeout = findViewById(R.id.edtTimeoutGuest);
        edtTimein = findViewById(R.id.edtTimeinGuest);
        divSpinner = findViewById(R.id.guest_div_spinner);
        depSpinner = findViewById(R.id.guest_dep_spinner);
        monitoring = findViewById(R.id.btn_monitoring_guest);
        submit = findViewById(R.id.submitGuest);
        progressDialog = new ProgressDialog(GuestPermissionActivity.this);

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        getDivision();
        divisionList = new ArrayList<>();
        departmentList = new ArrayList<>();

        divSpinnerAdapter = new ArrayAdapter<>(GuestPermissionActivity.this,
                android.R.layout.simple_spinner_dropdown_item, divisionList);
        divSpinner.setAdapter(divSpinnerAdapter);
        divSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                depSpinnerAdapter = new ArrayAdapter<>(GuestPermissionActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                depSpinner.setAdapter(depSpinnerAdapter);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        calGuest.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        GuestPermissionActivity.this, new DatePickerDialog.OnDateSetListener() {
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

        monitoring.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openMonitoringGuest();
            }
        });

        submit.setOnClickListener(view -> {
            saveData();
        });
    }

    public void pop(View view)
    {
        TimePickerDialog.OnTimeSetListener onTimeSetListener = new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker timePicker, int selectedHour, int selectedMinute) {
                hour = selectedHour;
                minute = selectedMinute;
                edtTimein.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
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
                edtTimeout.setText(String.format(Locale.getDefault(),"%02d:%02d",hour,minute));
            }
        };

        TimePickerDialog timePickerDialog = new TimePickerDialog(this,onTimeSetListener,hour,minute,true);

        timePickerDialog.setTitle("Select Time");
        timePickerDialog.show();
    }

    public void openMonitoringGuest() {
        Intent intent = new Intent(this, MonitoringGuest.class);
        startActivity(intent);
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
                Toast.makeText(GuestPermissionActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void saveData(){
        try {
            if (TextUtils.isEmpty(edtName.getText().toString()) || TextUtils.isEmpty(edtCompany.getText().toString()) || TextUtils.isEmpty(edtPhone.getText().toString()) || TextUtils.isEmpty(edtDate.getText().toString()) || TextUtils.isEmpty(edtNecessity.getText().toString()) || TextUtils.isEmpty(edtPic.getText().toString()) || TextUtils.isEmpty(edtTimeout.getText().toString()) || TextUtils.isEmpty(edtTimein.getText().toString())){
                Alerter.create(GuestPermissionActivity.this)
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
            } else {
                name = edtName.getText().toString();
                company = edtCompany.getText().toString();
                phone = edtPhone.getText().toString();
                pic = edtPic.getText().toString();
                necessity = edtNecessity.getText().toString();
                date = edtDate.getText().toString();
                timeIn = edtTimein.getText().toString();
                timeOut = edtTimeout.getText().toString();
                division = divSpinner.getSelectedItem().toString();
                department = depSpinner.getSelectedItem().toString();

                progressDialog.setContentView(R.layout.progress_dialog);
                progressDialog.getWindow().setBackgroundDrawableResource(
                        android.R.color.transparent
                );
                Thread timer = new Thread(){
                    @Override
                    public void run() {
                        try {
                            sleep(2000);
                            Intent intent = new Intent(getApplicationContext(), GuestPermissionActivity.class);
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

                Guest guest = new Guest(
                        db.collection("permission_guest").document().getId(),
                        name,
                        company,
                        phone,
                        division,
                        department,
                        pic,
                        necessity,
                        date,
                        timeIn,
                        timeOut,
                        "Pending",
                        "Pending"
                );
                db.collection("permission_guest").add(guest).addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
                    @Override
                    public void onSuccess(DocumentReference documentReference) {
                        StyleableToast.makeText(getApplicationContext(),"Data Send Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                    }
                }).addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(getApplicationContext(),"Data Send Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        progressDialog.dismiss();
                    }
                });
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }
}