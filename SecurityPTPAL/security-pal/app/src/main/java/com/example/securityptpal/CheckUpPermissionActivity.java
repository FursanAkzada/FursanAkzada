package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.DatePickerDialog;
import android.app.ProgressDialog;
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
import android.widget.Toast;

import com.example.securityptpal.main.AkunUtama;
import com.example.securityptpal.model.CheckUp;
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

public class CheckUpPermissionActivity extends AppCompatActivity {

    private ImageView imgSignOut;
    Spinner spinner, spinner1, spinner2, spinner3;
    Button submitCheckup, monitoring;
    TextView edtDepartCheckup;
    private ProgressDialog progressDialog;
    String name, nip, division, department, status, date, type, others;
    EditText edtDateCheckup, edtNameCheckup, edtNIPCheckup, edtOthersCheckup;
    ImageView dateCheckup;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayAdapter divSpinnerAdapter, depSpinnerAdapter;
    private List<Division> departments;
    ArrayList<String> divisionList, departmentList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_check_up);
        spinner = findViewById(R.id.spinner_division_checkup);
        spinner1 = findViewById(R.id.spinner_dep_checkup);
        spinner2 = findViewById(R.id.spinner_status_checkup);
        spinner3 = findViewById(R.id.spinner_type_checkup);
        edtDateCheckup = findViewById(R.id.edtDateCheckup);
        edtNameCheckup = findViewById(R.id.edtNameCheckup);
        edtNIPCheckup = findViewById(R.id.edtNIPCheckup);
        edtOthersCheckup = findViewById(R.id.edtOthersCheckup);
        submitCheckup = findViewById(R.id.submitCheckup);
        dateCheckup = findViewById(R.id.dateCheckup);
        monitoring = findViewById(R.id.gotoMonitoring);
        progressDialog = new ProgressDialog(CheckUpPermissionActivity.this);
        imgSignOut = findViewById(R.id.sign_out_checkup_account);

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        imgSignOut.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                LogoutAccount.logout(CheckUpPermissionActivity.this);
            }
        });

        monitoring.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openMonitoringCheckup();
            }
        });

         getDivision();
        divisionList = new ArrayList<>();
        departmentList = new ArrayList<>();

        divSpinnerAdapter = new ArrayAdapter<>(CheckUpPermissionActivity.this,
                android.R.layout.simple_spinner_dropdown_item, divisionList);
        spinner.setAdapter(divSpinnerAdapter);
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                depSpinnerAdapter = new ArrayAdapter<>(CheckUpPermissionActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                spinner1.setAdapter(depSpinnerAdapter);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        dateCheckup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        CheckUpPermissionActivity.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        month = month + 1;
                        String date = year+"/"+month+"/"+day;
                        edtDateCheckup.setText(date);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

        submitCheckup.setOnClickListener(view -> {
            try{
                if (TextUtils.isEmpty(edtNameCheckup.getText().toString()) || TextUtils.isEmpty(edtNIPCheckup.getText().toString()) || TextUtils.isEmpty(edtDateCheckup.getText().toString()) || TextUtils.isEmpty(edtOthersCheckup.getText().toString())){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                    Alerter.create(CheckUpPermissionActivity.this)
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
                    name = edtNameCheckup.getText().toString();
                    nip = edtNIPCheckup.getText().toString();
                    division = spinner.getSelectedItem().toString();
                    department = spinner1.getSelectedItem().toString();
                    status = spinner2.getSelectedItem().toString();
                    date = edtDateCheckup.getText().toString();
                    type = spinner3.getSelectedItem().toString();
                    others = edtOthersCheckup.getText().toString();

                    progressDialog = new ProgressDialog(CheckUpPermissionActivity.this);
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
                                Intent intent = new Intent(getApplicationContext(), CheckUpPermissionActivity.class);
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
                    CheckUp checkup = new CheckUp(
                            db.collection("permission_checkup").document().getId(),
                            name,
                            nip,
                            division,
                            department,
                            status,
                            date,
                            type,
                            others,
                            "Pending",
                            "Pending"
                    );
                    db.collection("permission_checkup").add(checkup).addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
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

        ArrayList<String> statusList = new ArrayList<>();

        statusList.add("PKWT");
        statusList.add("PKWTT");

        spinner2.setAdapter(new ArrayAdapter<>(CheckUpPermissionActivity.this,
                android.R.layout.simple_spinner_dropdown_item, statusList));

        spinner2.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
//                if (i == 0){
//                    Toast.makeText(getApplicationContext(),
//                            "Please Select Division",Toast.LENGTH_SHORT).show();
//                    textView.setText("");
//                }else{
//                    String sNumber = adapterView.getItemAtPosition(i).toString();
//                    textView.setText(sNumber);
//                }
                String sNumber = adapterView.getItemAtPosition(i).toString();
//                textView.setText(sNumber);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        ArrayList<String> typeList = new ArrayList<>();

        typeList.add("Blood Pressure Check");
        typeList.add("Cholesterol and Blood Sugar Check");
        typeList.add("Blood Test");
        typeList.add("Heart Check");
        typeList.add("Eye Examination");

        spinner3.setAdapter(new ArrayAdapter<>(CheckUpPermissionActivity.this,
                android.R.layout.simple_spinner_dropdown_item, typeList));

        spinner3.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
//                if (i == 0){
//                    Toast.makeText(getApplicationContext(),
//                            "Please Select Division",Toast.LENGTH_SHORT).show();
//                    textView.setText("");
//                }else{
//                    String sNumber = adapterView.getItemAtPosition(i).toString();
//                    textView.setText(sNumber);
//                }
                String sNumber = adapterView.getItemAtPosition(i).toString();
//                textView.setText(sNumber);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });
    }
    private void getDivision() {
        progressDialog = new ProgressDialog(CheckUpPermissionActivity.this);
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
                if (queryDocumentSnapshots.size() > 0) {
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
                Toast.makeText(CheckUpPermissionActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void openMonitoringCheckup() {
        Intent intent = new Intent(this, MonitoringCheckUp.class);
        startActivity(intent);
    }
}