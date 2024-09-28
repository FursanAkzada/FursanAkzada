package com.example.securityptpal.main;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.widget.TextView;

import com.example.securityptpal.R;
import com.example.securityptpal.adapter.DepartmentAdapter;
import com.example.securityptpal.adapter.DivisionAdapter;
import com.example.securityptpal.model.Division;

public class DetailDivisionActivity extends AppCompatActivity {

    private TextView name;
    private RecyclerView department;
    private DepartmentAdapter departmentAdapter;

    @SuppressLint("NotifyDataSetChanged")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_division);

        name = findViewById(R.id.detail_division_name);
        department = findViewById(R.id.detail_division_department);

        Division division = getIntent().getParcelableExtra("division");
        name.setText(division.getName());

        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        departmentAdapter = new DepartmentAdapter(division.getDepartment(), division.getId());
        department.setLayoutManager(layoutManager);
        department.addItemDecoration(decoration);
        department.setAdapter(departmentAdapter);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }
}