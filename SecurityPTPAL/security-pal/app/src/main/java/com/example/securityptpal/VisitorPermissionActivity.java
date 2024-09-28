package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

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
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.Visitor;
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

public class VisitorPermissionActivity extends AppCompatActivity {

    Button bt_visitor, submitVisitor;
    Spinner spinner, spinner2;
    private ProgressDialog progressDialog;
    EditText edtNameVisitor, edtCompanyVisitor, edtNoHPVisitor, edtPICVisitor, edtNecessityVisitor, edtDateVisitor, edtTimeoutVisitor, edtTimeinVisitor;
    String name, company, phone, division, department, pic, necessity, date, timein, timeout;
    ImageView dateVisitor, img_timeoutVisitor, img_timeinVisitor;
    int hour, minute;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayAdapter divSpinnerAdapter, depSpinnerAdapter;
    private List<Division> departments;
    ArrayList<String> divisionList, departmentList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_visitor);

        bt_visitor = findViewById(R.id.gotoMonitoring);
        spinner = findViewById(R.id.vis_div_spinner);
        spinner2 = findViewById(R.id.vis_dep_spinner);
        submitVisitor = findViewById(R.id.submitVisitor);
        edtNameVisitor = findViewById(R.id.edtNameVisitor);
        edtCompanyVisitor = findViewById(R.id.edtCompanyVisitor);
        edtNoHPVisitor = findViewById(R.id.edtNoHPVisitor);
        edtPICVisitor = findViewById(R.id.edtPICVisitor);
        edtNecessityVisitor = findViewById(R.id.edtNecessityVisitor);
        edtDateVisitor = findViewById(R.id.edtDateVisitor);
        edtTimeoutVisitor = findViewById(R.id.edtTimeoutVisitor);
        edtTimeinVisitor = findViewById(R.id.edtTimeinVisitor);
        dateVisitor = findViewById(R.id.dateVisitor);
        img_timeoutVisitor = findViewById(R.id.img_timeoutVisitor);
        img_timeinVisitor = findViewById(R.id.img_timeinVisitor);
        progressDialog = new ProgressDialog(VisitorPermissionActivity.this);

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        getDivision();
        divisionList = new ArrayList<>();
        departmentList = new ArrayList<>();

        divSpinnerAdapter = new ArrayAdapter<>(VisitorPermissionActivity.this,
                android.R.layout.simple_spinner_dropdown_item, divisionList);
        spinner.setAdapter(divSpinnerAdapter);
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                depSpinnerAdapter = new ArrayAdapter<>(VisitorPermissionActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                spinner2.setAdapter(depSpinnerAdapter);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        dateVisitor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        VisitorPermissionActivity.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        month = month + 1;
                        String date = year+"/"+month+"/"+day;
                        edtDateVisitor.setText(date);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

        bt_visitor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) { openMonitoringVisitor();
            }
        });

        submitVisitor.setOnClickListener(view -> {
            try{
                if (TextUtils.isEmpty(edtNameVisitor.getText().toString()) || TextUtils.isEmpty(edtCompanyVisitor.getText().toString()) || TextUtils.isEmpty(edtNoHPVisitor.getText().toString()) || TextUtils.isEmpty(edtPICVisitor.getText().toString()) || TextUtils.isEmpty(edtNecessityVisitor.getText().toString()) || TextUtils.isEmpty(edtDateVisitor.getText().toString()) || TextUtils.isEmpty(edtTimeoutVisitor.getText().toString()) || TextUtils.isEmpty(edtTimeinVisitor.getText().toString())){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                    Alerter.create(VisitorPermissionActivity.this)
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
                    name = edtNameVisitor.getText().toString();
                    company = edtCompanyVisitor.getText().toString();
                    phone = edtNoHPVisitor.getText().toString();
                    division = spinner.getSelectedItem().toString();
                    department = spinner2.getSelectedItem().toString();
                    pic = edtPICVisitor.getText().toString();
                    necessity = edtNecessityVisitor.getText().toString();
                    date = edtDateVisitor.getText().toString();
                    timein = edtTimeinVisitor.getText().toString();
                    timeout = edtTimeoutVisitor.getText().toString();

                    progressDialog = new ProgressDialog(VisitorPermissionActivity.this);
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
                                Intent intent = new Intent(getApplicationContext(), VisitorPermissionActivity.class);
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
//                    new Handler().postDelayed(new Runnable() {
//                        @Override
//                        public void run() {
//                            //do something
//                            StyleableToast.makeText(getApplicationContext(),"Data Send Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
//                        }
//                    }, 2000 );
                    Visitor visitor = new Visitor(
                            db.collection("permission_visitor").document().getId(),
                            name,
                            company,
                            phone,
                            division,
                            department,
                            pic,
                            necessity,
                            date,
                            timein,
                            timeout,
                            "Pending",
                            "Pending"
                    );
                    db.collection("permission_visitor").add(visitor).addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
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
            }
            catch (Exception e){
                e.printStackTrace();
            }
        });
    }

    private void getDivision() {
        progressDialog = new ProgressDialog(VisitorPermissionActivity.this);
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
                Toast.makeText(VisitorPermissionActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
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

    private void openMonitoringVisitor() {
        Intent intent = new Intent(this, MonitoringVisitor.class);
        startActivity(intent);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startActivity(new Intent(this, MainActivity2.class));
        finish();
    }
}