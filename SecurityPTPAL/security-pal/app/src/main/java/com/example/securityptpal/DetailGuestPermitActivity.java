package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Guest;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;
import com.zolad.zoominimageview.ZoomInImageView;

import java.util.ArrayList;

public class DetailGuestPermitActivity extends AppCompatActivity implements AdapterView.OnItemSelectedListener{

    private TextView name, company, phone, division, department, pic, necessity, date, timeIn, timeOut, division_approve, center_approve;
    ProgressDialog progressDialog;
    Guest guest;
    Spinner spinner;
    private Button save;
    String item;
    FirebaseAuth mAuth = FirebaseAuth.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> centerStatus;
    ImageView permit_ttd;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_guest_permit);
        progressDialog = new ProgressDialog(DetailGuestPermitActivity.this);
        name = findViewById(R.id.detail_name_guest);
        company = findViewById(R.id.detail_company_guest);
        phone = findViewById(R.id.detail_phone_guest);
        division = findViewById(R.id.detail_division_guest);
        department = findViewById(R.id.detail_department_guest);
        pic = findViewById(R.id.detail_pic_guest);
        necessity = findViewById(R.id.detail_necessity_guest);
        date = findViewById(R.id.detail_date_guest);
        timeIn = findViewById(R.id.detail_timein_guest);
        timeOut = findViewById(R.id.detail_timeout_guest);
        division_approve = findViewById(R.id.main_division_approval_status);
        center_approve = findViewById(R.id.main_center_approval);
        spinner = findViewById(R.id.main_guest_status_editable);
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

        guest = getIntent().getParcelableExtra("MAIN_GUEST_PERMIT");

        name.setText(guest.getName());
        company.setText(guest.getCompany());
        phone.setText(guest.getPhone());
        division.setText(guest.getDivision());
        department.setText(guest.getDepartment());
        pic.setText(guest.getPic());
        necessity.setText(guest.getNecessity());
        date.setText(guest.getDate());
        timeIn.setText(guest.getTimeIn());
        timeOut.setText(guest.getTimeOut());

        if (guest.getDivision_approval().equals("Pending")){
            division_approve.setText(guest.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_orange_color));
        } else if (guest.getDivision_approval().equals("Accepted")){
            division_approve.setText(guest.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.main_green_color));
        } else {
            division_approve.setText(guest.getDivision_approval());
            division_approve.setTextColor(division_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (guest.getCenter_approval().equals("Pending")){
            center_approve.setText(guest.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
        } else if (guest.getCenter_approval().equals("Accepted")){
            center_approve.setText(guest.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
        } else {
            center_approve.setText(guest.getCenter_approval());
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
        }

        if (guest.getCenter_approval().equals("Accepted") && guest.getDivision_approval().equals("Accepted")){
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
        center_approve.setText(item);
        if (item.equals("Pending")){
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_orange_color));
        } else if (item.equals("Accepted")){
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.main_green_color));
        } else {
            center_approve.setTextColor(center_approve.getResources().getColor(R.color.cardColorRed));
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
            guest.setCenter_approval(item);
            String id = guest.getId();
            db.collection("permission_guest").document(id).set(guest);
            StyleableToast.makeText(getApplicationContext(),"Change Status Successfull", Toast.LENGTH_SHORT,R.style.logsuccess).show();
        }
    }
}