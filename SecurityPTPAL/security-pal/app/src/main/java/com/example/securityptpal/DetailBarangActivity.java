package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.example.securityptpal.main.EditGoodsPermitActivity;
import com.example.securityptpal.model.Barang;
import com.zolad.zoominimageview.ZoomInImageView;

public class DetailBarangActivity extends AppCompatActivity {

    TextView division, department, type, txtDate, txtDevice, txtLatitude, txtLongitude, txtLocation, nameGoods, noHPGoods, picGoods, itemGoods, status, hintQR;
    ZoomInImageView imageView;
    ProgressDialog progressDialog;
    Barang barang;
    ImageView qrCode;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_barang);

        progressDialog = new ProgressDialog(DetailBarangActivity.this);
        imageView = findViewById(R.id.goods_detail_image);
        txtDate = findViewById(R.id.goods_detail_date);
        txtDevice = findViewById(R.id.goods_detail_device);
        txtLatitude = (TextView) findViewById(R.id.goods_detail_latitude);
        txtLongitude = (TextView) findViewById(R.id.goods_detail_longitude);
        txtLocation = (TextView) findViewById(R.id.goods_detail_location);
        nameGoods = findViewById(R.id.goods_detail_name);
        noHPGoods = findViewById(R.id.goods_detail_phone);
        picGoods = findViewById(R.id.goods_detail_pic);
        itemGoods = findViewById(R.id.goods_detail_item);
        division = findViewById(R.id.goods_detail_division);
        department = findViewById(R.id.goods_detail_department);
        type = findViewById(R.id.goods_detail_type);
        status = findViewById(R.id.goods_detail_status);
        qrCode = findViewById(R.id.goods_detail_qr);
        hintQR = findViewById(R.id.detail_hint_showqr);

        barang = getIntent().getParcelableExtra("MAIN_GOODS_PERMIT");

        nameGoods.setText(barang.getName());
        noHPGoods.setText(barang.getPhone());
        picGoods.setText(barang.getPic());
        division.setText(barang.getDivision());
        department.setText(barang.getDepartment());
        itemGoods.setText(barang.getGoods_name());
        type.setText(barang.getType());
        txtDate.setText(barang.getDate());
        txtDevice.setText(barang.getDevice());
        txtLatitude.setText(barang.getLatitude());
        txtLongitude.setText(barang.getLongitude());
        txtLocation.setText(barang.getLocation());
        Glide.with(this).load(barang.getImg()).placeholder(R.drawable.pict).into(imageView);
        qrCode.setOnClickListener(view -> {
            startActivity(new Intent(DetailBarangActivity.this, ShowQR.class));
        });

        if (barang.getStatus().equals("Pending")){
            status.setText(barang.getStatus());
            status.setTextColor(status.getResources().getColor(R.color.main_orange_color));
        } else if (barang.getStatus().equals("Accepted")){
            status.setText(barang.getStatus());
            status.setTextColor(status.getResources().getColor(R.color.main_green_color));
            qrCode.setVisibility(View.VISIBLE);
            hintQR.setVisibility(View.VISIBLE);
        } else {
            status.setText(barang.getStatus());
            status.setTextColor(status.getResources().getColor(R.color.cardColorRed));
        }
    }
}