package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.example.securityptpal.model.PermissionLate;

public class UtamaCometoolate extends AppCompatActivity {

    private TextView name, nip, division, date, reason, device, latitude, longitude, location, status, department;
    private ImageView imgEvidence;
    PermissionLate permissionLate;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_utama_cometoolate);

        name = findViewById(R.id.main_late_name);
        nip = findViewById(R.id.main_late_nip);
        division = findViewById(R.id.main_late_division);
        reason = findViewById(R.id.main_late_reason);
        date = findViewById(R.id.main_late_date);
        device = findViewById(R.id.main_late_device);
        latitude = findViewById(R.id.main_late_latitude);
        longitude = findViewById(R.id.main_late_longitude);
        location = findViewById(R.id.main_late_location);
        imgEvidence = findViewById(R.id.main_image_late);
        status = findViewById(R.id.main_late_status);
        department = findViewById(R.id.main_late_department);

        permissionLate = getIntent().getParcelableExtra("MAIN_LATE_PERMIT");
        name.setText(permissionLate.getName());
        nip.setText(permissionLate.getNip());
        division.setText(permissionLate.getDivision());
        reason.setText(permissionLate.getReason());
        date.setText(permissionLate.getDate());
        device.setText(permissionLate.getDevice());
        latitude.setText(permissionLate.getLatitude());
        longitude.setText(permissionLate.getLongitude());
        location.setText(permissionLate.getLocation());
        status.setText(permissionLate.getEmployee_status());
        department.setText(permissionLate.getDepartment());
        Glide.with(this).load(permissionLate.getImg()).placeholder(R.drawable.pict).into(imgEvidence);
    }
}