package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Spinner;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Subcon;
import com.zolad.zoominimageview.ZoomInImageView;

public class DetailSubconEmployeeActivity extends AppCompatActivity {

    EmployeeSubcon employeeSubcon;
    TextView period, name, phone, pos, ttl, address, startDate, finishDate, company, divisi, depart, loc,id;
    ZoomInImageView photo;
    Subcon subcon;
    ConstraintLayout download;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_subcon_employee);

        period = findViewById(R.id.detail_period_employee_subcon);
        name = findViewById(R.id.detail_name_employee_subcon);
        phone = findViewById(R.id.detail_phone_employee_subcon);
        pos = findViewById(R.id.detail_pos_employee_subcon);
        ttl = findViewById(R.id.detail_ttl_employee_subcon);
        address = findViewById(R.id.detail_address_employee_subcon);
        company = findViewById(R.id.detail_company_employee_subcon);
        startDate = findViewById(R.id.detail_start_date_employee_subcon);
        finishDate = findViewById(R.id.detail_finish_date_employee_subcon);
        photo = findViewById(R.id.img_employee_subcon);
        download = findViewById(R.id.downloadidcard);
        divisi = findViewById(R.id.detail_divisi_employee_subcon);
        depart = findViewById(R.id.detail_dep_employee_subcon);
        loc = findViewById(R.id.detail_loc_employee_subcon);
        id = findViewById(R.id.detail_id_employee_subcon);

        employeeSubcon = getIntent().getParcelableExtra("SUBCON_EMPLOYEE_DETAIL");
        subcon = getIntent().getParcelableExtra("SUBCON_DATA");

        period.setText(employeeSubcon.getPeriode());
        name.setText(employeeSubcon.getName());
        phone.setText(employeeSubcon.getPhone());
        pos.setText(employeeSubcon.getPosition());
        ttl.setText(employeeSubcon.getTtl());
        address.setText(employeeSubcon.getAddress());
        company.setText(subcon.getCompany());
        startDate.setText(employeeSubcon.getStart());
        finishDate.setText(employeeSubcon.getFinish());
        divisi.setText(subcon.getDivision());
        depart.setText(subcon.getDepartment());
        loc.setText(employeeSubcon.getLocation());
        id.setText(employeeSubcon.getId());
        Glide.with(this).load(employeeSubcon.getImg()).placeholder(R.drawable.pict).into(photo);

        download.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (period.getText().toString().equals("Monthly")){
                    Intent intent = new Intent(DetailSubconEmployeeActivity.this, IdcardDetailSubcon.class);
                    intent.putExtra("IDCARD1", employeeSubcon);
                    intent.putExtra("IDCARD2", subcon);
                    startActivity(intent);
                }else {
                    Intent intent = new Intent(DetailSubconEmployeeActivity.this, IdcardDetailSubcon2.class);
                    intent.putExtra("IDCARD1", employeeSubcon);
                    intent.putExtra("IDCARD2", subcon);
                    startActivity(intent);
                }
            }
        });

        if (subcon.getDivision_approval().equals("Accepted") && subcon.getCenter_approval().equals("Accepted")) {
            download.setVisibility(View.VISIBLE);
        }
    }
}