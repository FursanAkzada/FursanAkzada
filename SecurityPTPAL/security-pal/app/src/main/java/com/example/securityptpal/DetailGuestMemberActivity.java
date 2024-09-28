package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.MemberGuest;
import com.example.securityptpal.model.Subcon;
import com.zolad.zoominimageview.ZoomInImageView;

public class DetailGuestMemberActivity extends AppCompatActivity {

    MemberGuest memberGuest;
    TextView name, company, phone, division, department, pic, necessity, date, timeIn, timeOut;
    Guest guest;
    ConstraintLayout download;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_guest_member);

        name = findViewById(R.id.name_guest);
        company = findViewById(R.id.company_guest);
        phone = findViewById(R.id.phone_guest);
        division = findViewById(R.id.division_guest);
        department = findViewById(R.id.department_guest);
        pic = findViewById(R.id.pic_guest);
        necessity = findViewById(R.id.necessity_guest);
        date = findViewById(R.id.date_guest);
        timeIn = findViewById(R.id.timein_guest);
        timeOut = findViewById(R.id.timeout_guest);
        download = findViewById(R.id.downloadidcard);

        memberGuest = getIntent().getParcelableExtra("GUEST_MEMBER_DETAIL");
        guest = getIntent().getParcelableExtra("MEMBER_DATA");

        name.setText(memberGuest.getName());
        company.setText(guest.getCompany());
        phone.setText(guest.getPhone());
        division.setText(guest.getDivision());
        department.setText(guest.getDepartment());
        pic.setText(guest.getPic());
        necessity.setText(guest.getNecessity());
        date.setText(guest.getDate());
        timeIn.setText(guest.getTimeIn());
        timeOut.setText(guest.getTimeOut());

        download.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(DetailGuestMemberActivity.this, IdcardDetailMemberGu.class);
                intent.putExtra("IDCARD1", memberGuest);
                intent.putExtra("IDCARD2", guest);
                startActivity(intent);
            }
        });
        if (guest.getDivision_approval().equals("Accepted") && guest.getCenter_approval().equals("Accepted")) {
            download.setVisibility(View.VISIBLE);
        }
    }
}