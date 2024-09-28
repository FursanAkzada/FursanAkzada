package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.net.Uri;
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
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Subcon;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.storage.FirebaseStorage;
import com.google.firebase.storage.StorageReference;
import com.google.firebase.storage.UploadTask;
import com.muddzdev.styleabletoast.StyleableToast;
import com.zolad.zoominimageview.ZoomInImageView;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;

public class EditEmployeeSubconActivity extends AppCompatActivity {
    private EditText name, phone, pos, ttl, address, startDate, finishDate, loc;
    private TextView period;
    private Button save, changeImage;
    private EmployeeSubcon employeeSubcon;
    private ZoomInImageView photo;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    FirebaseStorage firebaseStorage = FirebaseStorage.getInstance();
    Subcon subcon;
    int PICK_IMAGE_CODE = 200;
    Uri selectedImage;
    Spinner spinner;
    String periode;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_employee_subcon);

        name = findViewById(R.id.edit_subcon_employee_name);
        phone = findViewById(R.id.edit_subcon_employee_phone);
        address = findViewById(R.id.edit_subcon_employee_address);
        save = findViewById(R.id.submit_edit_subcon_employee);
        photo = findViewById(R.id.edit_photo_employee_subcon);
        changeImage = findViewById(R.id.edit_employee_photo);
        loc = findViewById(R.id.edit_subcon_employee_loc);
//        id = findViewById(R.id.detail_id_employee_subcon);
        period = findViewById(R.id.edit_subcon_employee_periode);
        spinner = findViewById(R.id.edit_subcon_employee_spinner);
        pos = findViewById(R.id.edit_subcon_employee_pos);
        ttl = findViewById(R.id.edit_subcon_employee_ttl);
        startDate = findViewById(R.id.edit_subcon_employee_start);
        finishDate = findViewById(R.id.edit_subcon_employee_finish);

        employeeSubcon = getIntent().getParcelableExtra("EDIT_SUBCON_EMPLOYEE_PERMIT");
        subcon = getIntent().getParcelableExtra("SUBCON_DATA_EDIT");

        period.setText(employeeSubcon.getPeriode());
        name.setText(employeeSubcon.getName());
        phone.setText(employeeSubcon.getPhone());
        pos.setText(employeeSubcon.getPosition());
        ttl.setText(employeeSubcon.getTtl());
        address.setText(employeeSubcon.getAddress());
        startDate.setText(employeeSubcon.getStart());
        finishDate.setText(employeeSubcon.getFinish());
        loc.setText(employeeSubcon.getLocation());
        Glide.with(this).load(employeeSubcon.getImg()).placeholder(R.drawable.pict).into(photo);

        ArrayList<String> periodList = new ArrayList<>();

        periodList.add("Weekly");
        periodList.add("Monthly");

        spinner.setAdapter(new ArrayAdapter<>(EditEmployeeSubconActivity.this,
                android.R.layout.simple_spinner_dropdown_item, periodList));

        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                periode = spinner.getSelectedItem().toString();
                period.setText(periode);
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        changeImage.setOnClickListener(view1 -> {
            Intent intentImage = new Intent();
            intentImage.setType("image/*");
            intentImage.setAction(Intent.ACTION_GET_CONTENT);
            startActivityForResult(intentImage, PICK_IMAGE_CODE);
        });

        save.setOnClickListener(view -> {
            db.collection("subcontractor").document(subcon.getId()).collection("employee").document(employeeSubcon.getId())
                    .update(
                            "periode", period.getText().toString(),
                            "name", name.getText().toString(),
                            "ttl", ttl.getText().toString(),
                            "phone", phone.getText().toString(),
                            "position", pos.getText().toString(),
                            "location", loc.getText().toString(),
                            "start", startDate.getText().toString(),
                            "finish", finishDate.getText().toString(),
                            "address", address.getText().toString()
//                            "img", photo.getText().toString(),
                    )
                    .addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) {
//                            StorageReference storageReference = firebaseStorage.getReference("subcon_employee").child(employeeSubcon.getImg());
//                            storageReference.putFile(selectedImage).addOnCompleteListener(task -> {
//                                if (task.isSuccessful()) {
//                                    storageReference.getDownloadUrl().addOnSuccessListener(new OnSuccessListener<Uri>() {
//                                        @Override
//                                        public void onSuccess(Uri uri) {
//                                            Toast.makeText(EditEmployeeSubconActivity.this, "Change Image Success", Toast.LENGTH_SHORT).show();
//                                        }
//                                    });
//                                } else {
//                                    Toast.makeText(EditEmployeeSubconActivity.this, "Gagal mengubah image", Toast.LENGTH_SHORT).show();
//                                }
//                            });
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

//    @Override
//    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
//        super.onActivityResult(requestCode, resultCode, data);
//
//        if (data != null) {
//            if (data.getData() != null) {
//                Uri uri = data.getData(); // filepath
//                FirebaseStorage storage = FirebaseStorage.getInstance();
//                long time = new Date().getTime();
//                StorageReference reference = storage.getReference().child("subcon_employee").child(employeeSubcon.getImg());
//                reference.putFile(uri).addOnCompleteListener(new OnCompleteListener<UploadTask.TaskSnapshot>() {
//                    @Override
//                    public void onComplete(@NonNull Task<UploadTask.TaskSnapshot> task) {
//                        if (task.isSuccessful()) {
//                            reference.getDownloadUrl().addOnSuccessListener(new OnSuccessListener<Uri>() {
//                                @Override
//                                public void onSuccess(Uri uri) {
//                                    String filePath = uri.toString();
//                                    HashMap<String, Object> obj = new HashMap<>();
//                                    obj.put("img", filePath);
//                                    db.collection("subcontractor").document(subcon.getId()).collection("employee").document(employeeSubcon.getId())
//                                            .update(
//                                                    "name", name.getText().toString(),
//                                                    "age", age.getText().toString(),
//                                                    "phone", phone.getText().toString(),
//                                                    "nip", nip.getText().toString(),
//                                                    "address", address.getText().toString()
//                                            );
//                                }
//                            });
//                        }
//                    }
//                });
//                photo.setImageURI(data.getData());
//                selectedImage = data.getData();
//            }
//        }
//    }
}