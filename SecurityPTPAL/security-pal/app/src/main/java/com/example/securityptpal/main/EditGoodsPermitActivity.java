package com.example.securityptpal.main;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.example.securityptpal.BarangActivity;
import com.example.securityptpal.R;
import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionEmployee;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;
import com.muddzdev.styleabletoast.StyleableToast;
import com.zolad.zoominimageview.ZoomInImageView;

import java.util.ArrayList;
import java.util.List;

public class EditGoodsPermitActivity extends AppCompatActivity {

    Spinner divSpinner, depSpinner, typeSpinner, statusSpinner;
    EditText edtNameGoods, edtNoHPGoods, edtPICGoods, edtItemGoods;
    Button submitGoods;
    ZoomInImageView imageView;
    TextView division, department, type, txtDate, txtDevice, txtLatitude, txtLongitude, txtLocation, status;
    String typeItem, divItem, depItem, statusItem;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private ArrayList<String> divisionList, departmentList, typeList, statusList;
    Barang barang;
    private ProgressDialog progressDialog;
    private ArrayAdapter<String> divisionAdapter, departmentAdapter, typeAdapter, statusAdapter;
    private List<Division> departments;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_goods_permit);

        progressDialog = new ProgressDialog(EditGoodsPermitActivity.this);
        divSpinner = findViewById(R.id.goods_edit_spinner_division);
        typeSpinner = findViewById(R.id.goods_edit_spinner_type);
        imageView = findViewById(R.id.goods_edit_image);
        txtDate = findViewById(R.id.goods_edit_date);
        txtDevice = findViewById(R.id.goods_edit_device);
        txtLatitude = (TextView) findViewById(R.id.goods_edit_latitude);
        txtLongitude = (TextView) findViewById(R.id.goods_edit_longitude);
        txtLocation = (TextView) findViewById(R.id.goods_edit_location);
        depSpinner = findViewById(R.id.goods_edit_spinner_department);
        submitGoods = findViewById(R.id.submit_edit_goods);
        edtNameGoods = findViewById(R.id.goods_edit_name);
        edtNoHPGoods = findViewById(R.id.goods_edit_phone);
        edtPICGoods = findViewById(R.id.goods_edit_pic);
        edtItemGoods = findViewById(R.id.goods_edit_item);
        division = findViewById(R.id.goods_edit_division);
        department = findViewById(R.id.goods_edit_department);
        type = findViewById(R.id.goods_edit_type);
        status = findViewById(R.id.goods_edit_status);
        statusSpinner = findViewById(R.id.goods_edit_spinner_status);

        statusList = new ArrayList<>();
        statusList.add("Accepted");
        statusList.add("Pending");
        statusList.add("Rejected");

        statusAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, statusList);
        statusAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        statusSpinner.setAdapter(statusAdapter);
        statusSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                statusItem = statusSpinner.getSelectedItem().toString();
                status.setText(statusItem);
                if (statusItem.equals("Pending")){
                    status.setTextColor(status.getResources().getColor(R.color.main_orange_color));
                } else if (statusItem.equals("Accepted")){
                    status.setTextColor(status.getResources().getColor(R.color.main_green_color));
                } else {
                    status.setTextColor(status.getResources().getColor(R.color.cardColorRed));
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        barang = getIntent().getParcelableExtra("MAIN_EDIT_GOODS_PERMIT");

        getDivision();
        divisionList = new ArrayList<>();
        divisionAdapter = new ArrayAdapter<>(getApplicationContext(), android.R.layout.simple_spinner_dropdown_item, divisionList);
        divisionAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        divSpinner.setAdapter(divisionAdapter);

        departmentList = new ArrayList<>();
        divSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                divItem = divSpinner.getSelectedItem().toString();
                division.setText(divItem);
                departmentList.clear();
                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                departmentAdapter = new ArrayAdapter<>(EditGoodsPermitActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                depSpinner.setAdapter(departmentAdapter);
                depSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                        depItem = depSpinner.getSelectedItem().toString();
                        department.setText(depItem);
                    }

                    @Override
                    public void onNothingSelected(AdapterView<?> adapterView) {

                    }
                });
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        typeList = new ArrayList<>();
        typeList.add("Heavy Goods");
        typeList.add("Light Goods");
        typeSpinner.setAdapter(new ArrayAdapter<>(EditGoodsPermitActivity.this,
                android.R.layout.simple_spinner_dropdown_item, typeList));

        edtNameGoods.setText(barang.getName());
        edtNoHPGoods.setText(barang.getPhone());
        edtPICGoods.setText(barang.getPic());
        division.setText(barang.getDivision());
        department.setText(barang.getDepartment());
        edtItemGoods.setText(barang.getGoods_name());
        type.setText(barang.getType());
        txtDate.setText(barang.getDate());
        txtDevice.setText(barang.getDevice());
        txtLatitude.setText(barang.getLatitude());
        txtLongitude.setText(barang.getLongitude());
        txtLocation.setText(barang.getLocation());
        Glide.with(this).load(barang.getImg()).placeholder(R.drawable.pict).into(imageView);

        if (barang.getStatus().equals("Pending")){
            status.setText(barang.getStatus());
            status.setTextColor(status.getResources().getColor(R.color.main_orange_color));
        } else if (barang.getStatus().equals("Accepted")){
            status.setText(barang.getStatus());
            status.setTextColor(status.getResources().getColor(R.color.main_green_color));
        } else {
            status.setText(barang.getStatus());
            status.setTextColor(status.getResources().getColor(R.color.cardColorRed));
        }

        submitGoods.setOnClickListener(view -> {
            db.collection("goods_permit").document(barang.getId())
                    .update(
                            "name", edtNameGoods.getText().toString(),
                            "phone", edtNoHPGoods.getText().toString(),
                            "pic", edtPICGoods.getText().toString(),
                            "division", division.getText().toString(),
                            "department", department.getText().toString(),
                            "goods_name", edtItemGoods.getText().toString(),
                            "type", type.getText().toString(),
                            "status", status.getText().toString()
                    )
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) {
                            StyleableToast.makeText(getApplicationContext(),"Data Edited Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            StyleableToast.makeText(getApplicationContext(),"Data Failed to Edit!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                    });
            finish();
        });
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
                progressDialog.hide();
                departments = queryDocumentSnapshots.toObjects(Division.class);
                if (queryDocumentSnapshots.size()>0) {
                    divisionList.clear();
                    for (DocumentSnapshot doc : queryDocumentSnapshots) {
                        divisionList.add(doc.getString("name"));
                    }
                    divisionAdapter.notifyDataSetChanged();
                }
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                progressDialog.hide();
                Toast.makeText(EditGoodsPermitActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}