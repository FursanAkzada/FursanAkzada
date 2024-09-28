package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import android.Manifest;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.provider.MediaStore;
import android.text.Html;
import android.text.TextUtils;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionLate;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;
import com.google.firebase.storage.FirebaseStorage;
import com.google.firebase.storage.StorageReference;
import com.google.firebase.storage.UploadTask;
import com.muddzdev.styleabletoast.StyleableToast;
import com.tapadoo.alerter.Alerter;
import com.tapadoo.alerter.OnHideAlertListener;
import com.tapadoo.alerter.OnShowAlertListener;
import com.zolad.zoominimageview.ZoomInImageView;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class BarangActivity extends AppCompatActivity {

    private String[] PERMISSIONS;
    Button monitoring, btnPicture, submitGoods;
    Spinner divSpinner, typesSpinner, depSpinner;
    ZoomInImageView imageView;
    TextView txtDate, txtDevice, txtLatitude, txtLongitude, txtLocation;
    Build build;
    String information;
    FusedLocationProviderClient fusedLocationProviderClient;
    FirebaseStorage storage = FirebaseStorage.getInstance();
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    EditText edtNameGoods, edtNoHPGoods, edtPICGoods, edtItemGoods;
    ArrayList<String> divisionList, typeList, departmentList;
    ArrayAdapter divSpinnerAdapter, typeSpinnerAdapter, depSpinnerAdapter;
    String name, phone, pic, division, department, goodsname, typesgoods, date, device, latitude, longitude, location;
    private List<Division> departments;
    ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_barang);

        divSpinner = findViewById(R.id.goods_spinner_division);
        typesSpinner = findViewById(R.id.goods_spinner_type);
        monitoring = findViewById(R.id.gotoMonitoring);
        btnPicture = findViewById(R.id.buttonPictureBarang);
        imageView = findViewById(R.id.imageViewBarang);
        txtDate = findViewById(R.id.txtDateBarang);
        txtDevice = findViewById(R.id.txtDeviceBarang);
        txtLatitude = (TextView) findViewById(R.id.txtLatitudeBarang);
        txtLongitude = (TextView) findViewById(R.id.txtLongitudeBarang);
        txtLocation = (TextView) findViewById(R.id.txtLocationBarang);
        depSpinner = findViewById(R.id.goods_spinner_department);
        submitGoods = findViewById(R.id.submitGoods);
        edtNameGoods = findViewById(R.id.edtNameGoods);
        edtNoHPGoods = findViewById(R.id.edtNoHPGoods);
        edtPICGoods = findViewById(R.id.edtPICGoods);
        edtItemGoods = findViewById(R.id.edtItemGoods);

        PERMISSIONS = new String[]{
                Manifest.permission.CAMERA,
                Manifest.permission.ACCESS_FINE_LOCATION
        };

        getDivision();
        divisionList = new ArrayList<>();
        typeList = new ArrayList<>();
        departmentList = new ArrayList<>();

        typeList.add("Heavy Goods");
        typeList.add("Light Goods");

        monitoring.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openMonitoringBarang();
            }
        });

        divSpinnerAdapter = new ArrayAdapter<>(BarangActivity.this,
                android.R.layout.simple_spinner_dropdown_item, divisionList);
        divSpinner.setAdapter(divSpinnerAdapter);
        divSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {

                departmentList.clear();
                for (String mDepartment : departments.get(i).getDepartment()){
                    departmentList.add(mDepartment);
                }
                depSpinnerAdapter = new ArrayAdapter<>(BarangActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, departmentList);
                depSpinner.setAdapter(depSpinnerAdapter);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        typesSpinner.setAdapter(new ArrayAdapter<>(BarangActivity.this,
                android.R.layout.simple_spinner_dropdown_item, typeList));

        btnPicture.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (!hasPermissions(BarangActivity.this,PERMISSIONS)){
                    ActivityCompat.requestPermissions(BarangActivity.this,PERMISSIONS,2);
                }
            }
        });

        submitGoods.setOnClickListener(view -> {
            try{
                if (TextUtils.isEmpty(edtNameGoods.getText().toString()) || TextUtils.isEmpty(edtNoHPGoods.getText().toString()) || TextUtils.isEmpty(edtPICGoods.getText().toString()) || TextUtils.isEmpty(edtPICGoods.getText().toString()) || TextUtils.isEmpty(edtItemGoods.getText().toString())){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                    Alerter.create(BarangActivity.this)
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
                    name = edtNameGoods.getText().toString();
                    phone = edtNoHPGoods.getText().toString();
                    pic = edtPICGoods.getText().toString();
                    division = divSpinner.getSelectedItem().toString();
                    department = depSpinner.getSelectedItem().toString();
                    goodsname = edtItemGoods.getText().toString();
                    typesgoods = typesSpinner.getSelectedItem().toString();
                    date = txtDate.getText().toString();
                    device = txtDevice.getText().toString();
                    latitude = txtLatitude.getText().toString();
                    longitude = txtLongitude.getText().toString();
                    location = txtLocation.getText().toString();
                    upload(name, phone, pic, division, department, goodsname, typesgoods, date, device, latitude, longitude, location);

                    progressDialog = new ProgressDialog(BarangActivity.this);
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
                                Intent intent = new Intent(getApplicationContext(), BarangActivity.class);
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
                    new Handler().postDelayed(new Runnable() {
                        @Override
                        public void run() {
                            //do something
                            StyleableToast.makeText(getApplicationContext(),"Data Send Successfully!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                        }
                    }, 2000 );
                }
            }
            catch (Exception e){
                e.printStackTrace();
            }
        });

        fusedLocationProviderClient = LocationServices.getFusedLocationProviderClient(this);
        saveinfo();
    }

    private void upload(String name, String phone, String pic, String division, String department, String goodsname, String typesgoods, String date, String device, String latitude, String longitude, String location) {
        imageView.setDrawingCacheEnabled(true);
        imageView.buildDrawingCache();
        Bitmap bitmap = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, baos);
        byte[] data = baos.toByteArray();

        StorageReference storageRef = storage.getReference("goods_permit").child("IMG" + new Date().getTime()+".jpeg");
        UploadTask uploadTask = storageRef.putBytes(data);
        uploadTask.addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception exception) {
                Toast.makeText(BarangActivity.this, exception.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
            }
        }).addOnSuccessListener(new OnSuccessListener<UploadTask.TaskSnapshot>() {
            @Override
            public void onSuccess(UploadTask.TaskSnapshot taskSnapshot) {
                if (taskSnapshot.getMetadata() != null) {
                    if (taskSnapshot.getMetadata().getReference() != null) {
                        taskSnapshot.getMetadata().getReference().getDownloadUrl().addOnCompleteListener(new OnCompleteListener<Uri>() {
                            @Override
                            public void onComplete(@NonNull Task<Uri> task) {
                                saveData(
                                        name,
                                        phone,
                                        pic,
                                        division,
                                        department,
                                        goodsname,
                                        typesgoods,
                                        date,
                                        device,
                                        latitude,
                                        longitude,
                                        location,
                                        task.getResult().toString()
                                );
                            }
                        });
                    }
                }
            }
        });
    }

    private void saveData(String name, String phone, String pic, String division, String department, String goodsname, String typesgoods, String date, String device, String latitude, String longitude, String location, String img) {
        Barang barang = new Barang(
                db.collection("goods_permit").document().getId(),
                name,
                phone,
                pic,
                division,
                department,
                goodsname,
                typesgoods,
                img,
                date,
                device,
                latitude,
                longitude,
                location,
                "Pending"
        );

        db.collection("goods_permit").add(barang).addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
            @Override
            public void onSuccess(DocumentReference documentReference) {
                StyleableToast.makeText(BarangActivity.this, "Data Send Succesfully", Toast.LENGTH_SHORT,R.style.logsuccess).show();
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                StyleableToast.makeText(getApplicationContext(),"Data Send Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                progressDialog.dismiss();
            }
        });
    }

    private void getDivision() {
        db.collection("division").get().addOnSuccessListener(new OnSuccessListener<QuerySnapshot>() {
            @Override
            public void onSuccess(QuerySnapshot queryDocumentSnapshots) {
                departments = queryDocumentSnapshots.toObjects(Division.class);
                if (queryDocumentSnapshots.size()>0) {
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
                StyleableToast.makeText(BarangActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT,R.style.resultfailed).show();
            }
        });
    }

    private boolean hasPermissions(Context context, String... PERMISSIONS){
        if (context != null && PERMISSIONS != null){
            for (String permission: PERMISSIONS){
                if (ActivityCompat.checkSelfPermission(context,permission) != PackageManager.PERMISSION_GRANTED){
                    return false;
                }
            }
        }
        return false;
    }

    private void getLocation() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return;
        }
        fusedLocationProviderClient.getLastLocation().addOnCompleteListener(new OnCompleteListener<Location>() {
            @Override
            public void onComplete(@NonNull Task<Location> task) {
                Location location = task.getResult();

                if (location != null) {
                    try {
                        Geocoder geocoder = new Geocoder(BarangActivity.this, Locale.getDefault());

                        List<Address> addresses = geocoder.getFromLocation(location.getLatitude(), location.getLongitude(), 1);
                        txtLatitude.setText(Html.fromHtml("<font color = '000000'></font>" + addresses.get(0).getLatitude()));
                        txtLongitude.setText(Html.fromHtml("<font color = '000000'></font>" + addresses.get(0).getLongitude()));
                        txtLocation.setText(Html.fromHtml("<font color = '000000'></font>" + addresses.get(0).getAddressLine(0)));
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }
        });
    }

    public void openMonitoringBarang() {
        Intent intent = new Intent(this, MonitoringBarang.class);
        startActivity(intent);
    }

    private void saveinfo(){
        information = build.MODEL;
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);

        if (requestCode == 2){
            if (grantResults[0] == PackageManager.PERMISSION_GRANTED){
//                Toast.makeText(this, "Calling Permissions is granted", Toast.LENGTH_SHORT).show();
                Intent intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                startActivityForResult(intent, 2);
            }else {
                StyleableToast.makeText(this, "You don't have permission to access camera!", Toast.LENGTH_SHORT,R.style.warning).show();
            }
            if (grantResults[1] == PackageManager.PERMISSION_GRANTED){
//                Toast.makeText(this, "SMS Permissions is granted", Toast.LENGTH_SHORT).show();
                getLocation();
            }else {
                StyleableToast.makeText(this, "You don't have permission to access location!", Toast.LENGTH_SHORT,R.style.warning).show();
            }
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if(requestCode == 2 && resultCode == RESULT_OK && data != null) {
            final Bundle extras = data.getExtras();
            Thread thread = new Thread(() -> {
                Bitmap bitmap = (Bitmap) extras.get("data");
                imageView.post(() -> {
                    imageView.setImageBitmap(bitmap);
                });
            });
            thread.start();
            Date dateCurrent = Calendar.getInstance().getTime();
            SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd/HH:mm:ss");
            String dateString = dateFormat.format(dateCurrent);
            txtDate.setText(dateString);
            txtDevice.setText(information);
            StyleableToast.makeText(getApplicationContext(), "Image has been Captured!", Toast.LENGTH_SHORT, R.style.result).show();
            getLocation();
        }else {
            StyleableToast.makeText(getApplicationContext(), "No Image Captured!", Toast.LENGTH_SHORT, R.style.warning).show();
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startActivity(new Intent(this, MainActivity.class));
        finish();
    }
}