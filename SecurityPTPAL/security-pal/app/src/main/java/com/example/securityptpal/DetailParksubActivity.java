package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;

import com.example.securityptpal.model.MasukPS;

public class DetailParksubActivity extends AppCompatActivity {

    MasukPS masukPS;
    TextView name, nopol, date, queue;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_parksub);
        name = findViewById(R.id.detail_employee_name_parksub);
        nopol = findViewById(R.id.detail_employee_nopol_parksub);
        date = findViewById(R.id.detail_employee_date_parksub);
        queue = findViewById(R.id.detail_employee_queue_parksub);

        masukPS = getIntent().getParcelableExtra("DETAIL_PARKSUB");
        name.setText(masukPS.getName());
        nopol.setText(masukPS.getNopol());
        date.setText(masukPS.getDate());
        queue.setText(masukPS.getQueue());
    }
}