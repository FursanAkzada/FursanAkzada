package com.example.securityptpal.division;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;

import com.example.securityptpal.CheckupPermitActivity;
import com.example.securityptpal.GuestPermitActivity;
import com.example.securityptpal.R;
import com.example.securityptpal.SubconPermitActivity;
import com.example.securityptpal.VisitorPermitActivity;

public class EmployeePermitActivity extends AppCompatActivity {

    private String EXTRA;
    private CardView cvLatePermission, cvExitPermission, cvVisitorPermission, cvCheckupPermission, cvGuestPermission, cvSubconPermission;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_employee_permit);
        cvExitPermission = findViewById(R.id.card_exit_permission);
        cvLatePermission = findViewById(R.id.card_late_permission);
        cvVisitorPermission = findViewById(R.id.card_vis_permission);
        cvCheckupPermission = findViewById(R.id.card_check_permission);
        cvGuestPermission = findViewById(R.id.card_guest_permission);
        cvSubconPermission = findViewById(R.id.card_sub_permission);

        Bundle extras = getIntent().getExtras();
        EXTRA = extras.getString(Intent.EXTRA_TEXT);
        cvExitPermission.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(EmployeePermitActivity.this, ExitPermissionActivity.class);
                intent.putExtra(Intent.EXTRA_TEXT, EXTRA);
                startActivity(intent);
            }
        });

        cvLatePermission.setOnClickListener(view -> {
            Intent intent = new Intent(EmployeePermitActivity.this, DivisionLatePermitActivity.class);
            intent.putExtra(Intent.EXTRA_TEXT, EXTRA);
            startActivity(intent);
        });

        cvVisitorPermission.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(EmployeePermitActivity.this, VisitorPermitActivity.class);
                intent.putExtra(Intent.EXTRA_TEXT, EXTRA);
                startActivity(intent);
            }
        });

        cvCheckupPermission.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(EmployeePermitActivity.this, CheckupPermitActivity.class);
                intent.putExtra(Intent.EXTRA_TEXT, EXTRA);
                startActivity(intent);
            }
        });

        cvGuestPermission.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(EmployeePermitActivity.this, GuestPermitActivity.class);
                intent.putExtra(Intent.EXTRA_TEXT, EXTRA);
                startActivity(intent);
            }
        });

        cvSubconPermission.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(EmployeePermitActivity.this, SubconPermitActivity.class);
                intent.putExtra(Intent.EXTRA_TEXT, EXTRA);
                startActivity(intent);
            }
        });
    }
}