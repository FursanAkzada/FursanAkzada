package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.core.app.ActivityCompat;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.media.Image;
import android.net.Uri;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;

import com.example.securityptpal.adapter.MonitoringGoodsPermitAdapter;
import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.adapter.OnPermitLongClick;
import com.example.securityptpal.adapter.SubconAdapter;
import com.example.securityptpal.employee.Employee;
import com.example.securityptpal.main.EditSubconPermitActivity;
import com.example.securityptpal.main.MainDivisionActivity;
import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.PermissionEmployee;
import com.example.securityptpal.model.Subcon;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
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
import java.io.FileNotFoundException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class MonitoringSubcontractor extends AppCompatActivity implements OnPermitListener, OnPermitLongClick{
    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<Subcon> list = new ArrayList<>();
    private SubconAdapter subconAdapter;
    private ProgressDialog progressDialog;
    private SearchView searchView;
    String userID, imgUrl;
    FirebaseAuth mAuth;
    AlertDialog dialog;
    ZoomInImageView imageView;
    FirebaseStorage storage = FirebaseStorage.getInstance();
    private static final int SELECT_PHOTO = 100;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_monitoring_subcontractor);
        recyclerView = findViewById(R.id.rv_monitoring_subcon);
        searchView = findViewById(R.id.search_subcon);
        searchView.clearFocus();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchData(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                //searchData(newText);

                return false;
            }
        });
        progressDialog = new ProgressDialog(MonitoringSubcontractor.this);
        mAuth = FirebaseAuth.getInstance();
        userID = mAuth.getUid();

        subconAdapter = new SubconAdapter(this, list, this, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(subconAdapter);

        showAllDataDesc();
    }

    private void searchData(String company) {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("subcontractor")
                .whereEqualTo("company", company)
                .orderBy("startDate", Query.Direction.DESCENDING)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                Subcon subcon = new Subcon(
                                        document.getId(),
                                        document.getString("company"),
                                        document.getString("necessity"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("userID"),
                                        document.getString("division_approval"),
                                        document.getString("center_approval")
                                );
                                list.add(subcon);
                            }
                            subconAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            StyleableToast.makeText(getApplicationContext(), "Data Failed to Load", Toast.LENGTH_SHORT, R.style.warning).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(getApplicationContext(), "Data Not Found!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                    }
                });
    }

    private void showAllDataDesc() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("subcontractor").whereEqualTo("userID", userID).addSnapshotListener(new EventListener<QuerySnapshot>() {
            @SuppressLint("NotifyDataSetChanged")
            @Override
            public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                list.clear();
                if (value != null) {
                    for (QueryDocumentSnapshot document : value) {
                        Subcon subcon = new Subcon(
                                document.getId(),
                                document.getString("company"),
                                document.getString("necessity"),
                                document.getString("division"),
                                document.getString("department"),
                                document.getString("userID"),
                                document.getString("division_approval"),
                                document.getString("center_approval")
                        );
                        list.add(subcon);
                    }
                    subconAdapter.notifyDataSetChanged();
                } else {
                    StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                }
                progressDialog.dismiss();
            }
        });
    }

    private void deleteData(String id) {
        progressDialog.setTitle("Loading");
        progressDialog.setMessage("Deleting data...");
        progressDialog.show();
        db.collection("subcontractor").document(id)
                .delete()
                .addOnCompleteListener(new OnCompleteListener<Void>() {
                    @Override
                    public void onComplete(@NonNull Task<Void> task) {
                        if (!task.isSuccessful()){
                            Toast.makeText(getApplicationContext(), "Data gagal di hapus!", Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(getApplicationContext(), "Data berhasil di hapus!", Toast.LENGTH_SHORT).show();
                        }
                        progressDialog.dismiss();
                        showAllDataDesc();
                    }
                });
    }

    private void addEmployee(List<Subcon> subcons, int pos){
        AlertDialog.Builder builder = new AlertDialog.Builder(MonitoringSubcontractor.this);
        builder.setTitle("Add Employee");
        View view = getLayoutInflater().inflate(R.layout.add_employee_subcon, null);

        java.util.Calendar calendar = java.util.Calendar.getInstance();
        final int year = calendar.get(java.util.Calendar.YEAR);
        final int month = calendar.get(java.util.Calendar.MONTH);
        final int day = calendar.get(java.util.Calendar.DAY_OF_MONTH);

        Spinner spinner = (Spinner) view.findViewById(R.id.spinner_periode);

        ArrayList<String> periodList = new ArrayList<>();

        periodList.add("Weekly");
        periodList.add("Monthly");

        spinner.setAdapter(new ArrayAdapter<>(MonitoringSubcontractor.this,
                android.R.layout.simple_spinner_dropdown_item, periodList));

        ImageView calStart = (ImageView) view.findViewById(R.id.calstartSub);
        ImageView calFinish = (ImageView) view.findViewById(R.id.calfinishSub);
        EditText edtName = (EditText) view.findViewById(R.id.name_employee);
        EditText edtPosition = (EditText) view.findViewById(R.id.pos_employee);
        EditText edtPhone = (EditText) view.findViewById(R.id.phone_employee);
        EditText edtLocation = (EditText) view.findViewById(R.id.location_employee);
        EditText edtttl = (EditText) view.findViewById(R.id.ttl_employee);
        EditText edtaddress = (EditText) view.findViewById(R.id.address_employee);
        EditText edtstart = (EditText) view.findViewById(R.id.startEmployee);
        EditText edtfinish = (EditText) view.findViewById(R.id.finishEmployee);
        imageView = view.findViewById(R.id.img_employee_subcon);
        Button btnAddPhoto = view.findViewById(R.id.add_employee_photo);
        Button btnSubmit = view.findViewById(R.id.btn_add_employee);

        calStart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        MonitoringSubcontractor.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        month = month + 1;
                        String date = year+"/"+month+"/"+day;
                        edtstart.setText(date);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

        calFinish.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                DatePickerDialog datePickerDialog = new DatePickerDialog(
                        MonitoringSubcontractor.this, new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int month, int day) {
                        month = month + 1;
                        String date = year+"/"+month+"/"+day;
                        edtfinish.setText(date);
                    }
                },year,month,day);
                datePickerDialog.show();
            }
        });

        btnAddPhoto.setOnClickListener(view1 -> {
            ActivityCompat.requestPermissions(
                    MonitoringSubcontractor.this,
                    new String[]{Manifest.permission.READ_EXTERNAL_STORAGE},
                    SELECT_PHOTO
            );
        });

        btnSubmit.setOnClickListener(view1 -> {
            if (TextUtils.isEmpty(edtName.getText().toString()) || TextUtils.isEmpty(edtttl.getText().toString())|| TextUtils.isEmpty(edtPosition.getText().toString())|| TextUtils.isEmpty(edtPhone.getText().toString())|| TextUtils.isEmpty(edtLocation.getText().toString())|| TextUtils.isEmpty(edtstart.getText().toString())|| TextUtils.isEmpty(edtfinish.getText().toString())|| TextUtils.isEmpty(edtaddress.getText().toString())){
//                        StyleableToast.makeText(getApplicationContext(), "Please fill all the data!!!", Toast.LENGTH_SHORT, R.style.resultfailed).show();
                Alerter.create(MonitoringSubcontractor.this)
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
            } else {
                //Convert Image to bitmap
                imageView.setDrawingCacheEnabled(true);
                imageView.buildDrawingCache();
                Bitmap bitmap = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
                ByteArrayOutputStream baos = new ByteArrayOutputStream();
                bitmap.compress(Bitmap.CompressFormat.JPEG, 100, baos);
                byte[] data = baos.toByteArray();

                progressDialog.setTitle("Loading");
                progressDialog.setMessage("Sending data...");
                progressDialog.show();
                StorageReference storageRef = storage.getReference("subcon_employee").child("IMG" + new Date().getTime()+".jpeg");
                UploadTask uploadTask = storageRef.putBytes(data);
                uploadTask.addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception exception) {
                        Toast.makeText(MonitoringSubcontractor.this, exception.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
                    }
                }).addOnSuccessListener(new OnSuccessListener<UploadTask.TaskSnapshot>() {
                    @Override
                    public void onSuccess(UploadTask.TaskSnapshot taskSnapshot) {
                        if (taskSnapshot.getMetadata() != null) {
                            if (taskSnapshot.getMetadata().getReference() != null) {
                                taskSnapshot.getMetadata().getReference().getDownloadUrl().addOnCompleteListener(new OnCompleteListener<Uri>() {
                                    @Override
                                    public void onComplete(@NonNull Task<Uri> task) {
                                        EmployeeSubcon employeeSubcon = new EmployeeSubcon(
                                                db.collection("subcontractor").document(subcons.get(pos).getId()).collection("employee").document().getId(),
                                                spinner.getSelectedItem().toString(),
                                                edtName.getText().toString(),
                                                edtttl.getText().toString(),
                                                edtPhone.getText().toString(),
                                                edtPosition.getText().toString(),
                                                edtLocation.getText().toString(),
                                                edtstart.getText().toString(),
                                                edtfinish.getText().toString(),
                                                edtaddress.getText().toString(),
                                                task.getResult().toString()
                                        );
                                        db.collection("subcontractor")
                                                .document(subcons.get(pos).getId())
                                                .collection("employee")
                                                .add(employeeSubcon)
                                                .addOnSuccessListener(new OnSuccessListener<DocumentReference>() {
                                                    @Override
                                                    public void onSuccess(DocumentReference documentReference) {
                                                        StyleableToast.makeText(MonitoringSubcontractor.this, "Success adding employee", Toast.LENGTH_SHORT, R.style.logsuccess).show();
                                                    }
                                                }).addOnFailureListener(new OnFailureListener() {
                                            @Override
                                            public void onFailure(@NonNull Exception e) {
                                                StyleableToast.makeText(MonitoringSubcontractor.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT, R.style.resultfailed).show();
                                                    }
                                                });
                                    }
                                });
                            }
                        }
                    }
                });
                dialog.dismiss();
                progressDialog.dismiss();
            }
        });

        builder.setView(view);
        dialog = builder.create();
        dialog.show();
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {

        if(requestCode == SELECT_PHOTO) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                Intent intent = new Intent(Intent.ACTION_PICK);
                intent.setType("image/*");
                startActivityForResult(intent, SELECT_PHOTO);
            } else {
                StyleableToast.makeText(getApplicationContext(), "You don't have permission to access file location!", Toast.LENGTH_SHORT, R.style.warning).show();
            }
            return;
        }
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode,@Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == SELECT_PHOTO) {
            if (resultCode == RESULT_OK) {
                Uri selectImage = data.getData();
                InputStream inputStream = null;

                try {
                    assert selectImage != null;
                    inputStream = getContentResolver().openInputStream(selectImage);
                } catch (FileNotFoundException e) {
                    e.printStackTrace();
                }
                BitmapFactory.decodeStream(inputStream);
                imageView.setImageURI(selectImage);
            }
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }

    @Override
    public void onPermitClick(int position) {
        Intent intent = new Intent(MonitoringSubcontractor.this, DetailSubconActivity.class);
        intent.putExtra("SUBCON_DETAIL", list.get(position));
        startActivity(intent);
    }

    @Override
    public void onLongCLickListener(int pos) {
        final CharSequence[] dialogItem = {"Add Employee", "Edit", "Delete"};
        AlertDialog.Builder builder = new AlertDialog.Builder(MonitoringSubcontractor.this);
        View layout = getLayoutInflater().inflate(R.layout.edit_subconmain, null);
        Button btnEdit = layout.findViewById(R.id.btn_edt);
        Button btnDelete = layout.findViewById(R.id.btn_dlt);
        Button btnAdd = layout.findViewById(R.id.btn_add);

        btnEdit.setOnClickListener(view1 -> {
            Intent intentEdit = new Intent(getApplicationContext(), EditSubconPermitActivity.class);
            intentEdit.putExtra("MAIN_EDIT_SUBCON_PERMIT", list.get(pos));
            startActivity(intentEdit);
        });
        btnDelete.setOnClickListener(view1 -> {
            new SweetAlertDialog(MonitoringSubcontractor.this, SweetAlertDialog.WARNING_TYPE)
                    .setTitleText("Warning!!!")
                    .setContentText("Are you sure want to delete this data ?")
                    .setConfirmText("OK")
                    .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                        @Override
                        public void onClick(SweetAlertDialog sDialog) {
                            try {
                                deleteData(list.get(pos).getId());
                                sDialog.dismissWithAnimation();
                                StyleableToast.makeText(getApplicationContext(), "Delete Successfully!!!", Toast.LENGTH_SHORT, R.style.result).show();
                            } catch (Exception e) {
                                Log.e("error", e.getMessage());
                            }
                        }
                    })
                    .setCancelButton("CANCEL", new SweetAlertDialog.OnSweetClickListener() {
                        @Override
                        public void onClick(SweetAlertDialog sDialog) {
                            sDialog.dismissWithAnimation();
                        }
                    })
                    .show();
            dialog.dismiss();
        });
        btnAdd.setOnClickListener(view -> {
            addEmployee(list, pos);
        });
        builder.setView(layout);
        dialog = builder.create();
        dialog.show();
    }
}


//AlertDialog.Builder dialog = new AlertDialog.Builder(MonitoringSubcontractor.this);
//        dialog.setItems(dialogItem, new DialogInterface.OnClickListener() {
//
//            @Override
//            public void onClick(DialogInterface dialogInterface, int i) {
//                switch (i) {
//                    case 0:
//                        addEmployee(list, pos);
//                        break;
//                    case 1:
//                        Intent intentEdit = new Intent(getApplicationContext(), EditSubconPermitActivity.class);
//                        intentEdit.putExtra("MAIN_EDIT_SUBCON_PERMIT", list.get(pos));
//                        startActivity(intentEdit);
//                        break;
//                    case 2:
//                        deleteData(list.get(pos).getId());
//                        break;
//                }
//            }
//        });
//        dialog.show();
//    }
//}