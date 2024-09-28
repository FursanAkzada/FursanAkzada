package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.Manifest;

import android.Manifest;
import android.annotation.SuppressLint;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.example.securityptpal.adapter.MainGoodsPermitAdapter;
import com.example.securityptpal.adapter.OnPermitListener;
import com.example.securityptpal.adapter.OnPermitLongClick;
import com.example.securityptpal.main.AkunUtama;
import com.example.securityptpal.main.EditGoodsPermitActivity;
import com.example.securityptpal.main.UtamaDataEmployee;
import com.example.securityptpal.model.PermissionLate;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.firebase.firestore.EventListener;
import com.google.firebase.firestore.FirebaseFirestoreException;
import com.muddzdev.styleabletoast.StyleableToast;

import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import com.example.securityptpal.model.Barang;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
import com.google.firebase.storage.FirebaseStorage;
import com.muddzdev.styleabletoast.StyleableToast;

import org.apache.poi.hssf.usermodel.HSSFWorkbook;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class UtamaDataBarang extends AppCompatActivity implements OnPermitListener, OnPermitLongClick {

    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    private List<Barang> list = new ArrayList<>();
    private MainGoodsPermitAdapter mainGoodsPermitAdapter;
    private SearchView searchView;
    private Intent intent;
    private ImageView imgSignOut;
    private String userID;
    private ProgressDialog progressDialog;
    FloatingActionButton fab, fab1, fab2;
    Animation fabOpen, fabClose, rotateForward, rotateBackward;
    DrawerLayout drawerLayout;
    boolean isOpen = false;
    ImageView btMenu, filter;

    SwipeRefreshLayout mSwipeRefreshLayout;
    AlertDialog dialog;
    int filterCode = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_utama_data_barang);
        recyclerView = findViewById(R.id.rv_main_goods_permit);
        searchView = findViewById(R.id.main_search_goods);
        mSwipeRefreshLayout = findViewById(R.id.refresh_main_goods_permit);
        progressDialog = new ProgressDialog(UtamaDataBarang.this);
        fab = (FloatingActionButton) findViewById(R.id.main_goods_fab);
        fab1 = (FloatingActionButton) findViewById(R.id.main_goods_fab1);
        fab2 = (FloatingActionButton) findViewById(R.id.main_goods_fab2);

        fabOpen = AnimationUtils.loadAnimation(this, R.anim.fab_open);
        fabClose = AnimationUtils.loadAnimation(this, R.anim.fab_close);

        rotateForward = AnimationUtils.loadAnimation(this, R.anim.rotate_forward);
        rotateBackward = AnimationUtils.loadAnimation(this, R.anim.rotate_backward);
        drawerLayout = findViewById(R.id.drawer_layout);
        btMenu = findViewById(R.id.bt_menu);
        filter = findViewById(R.id.main_filter_goods);

        btMenu.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                drawerLayout.openDrawer(GravityCompat.START);
            }
        });

        fab = (FloatingActionButton) findViewById(R.id.main_goods_fab);
        fab1 = (FloatingActionButton) findViewById(R.id.main_goods_fab1);
        fab2 = (FloatingActionButton) findViewById(R.id.main_goods_fab2);

        fabOpen = AnimationUtils.loadAnimation(this, R.anim.fab_open);
        fabClose = AnimationUtils.loadAnimation(this, R.anim.fab_close);

        rotateForward = AnimationUtils.loadAnimation(this, R.anim.rotate_forward);
        rotateBackward = AnimationUtils.loadAnimation(this, R.anim.rotate_backward);

        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                animateFab();
            }
        });

        fab1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
//                if (Build.VERSION.SDK_INT > Build.VERSION_CODES.M) {
//                    if (getApplicationContext().checkSelfPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE) == PackageManager.PERMISSION_DENIED) {
//                        String[] permissions = {Manifest.permission.WRITE_EXTERNAL_STORAGE};
//                        requestPermissions(permissions, 1);
//                    } else {
//                        importData();
//                    }
//                } else {
//                    importData();
//                }
            }
        });
        fab2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
//                String foldername = "Import Excel";
//                Intent intent = new Intent(Intent.ACTION_PICK);
//                intent.setDataAndType(Uri.parse(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + foldername), "*/*");
//                startActivity(intent);
            }
        });

//        System.setProperty("org.apache.poi.javax.xml.stream.XMLInputFactory", "com.fasterxml.aalto.stax.InputFactoryImpl");
//        System.setProperty("org.apache.poi.javax.xml.stream.XMLOutputFactory", "com.fasterxml.aalto.stax.OutputFactoryImpl");
//        System.setProperty("org.apache.poi.javax.xml.stream.XMLEventFactory", "com.fasterxml.aalto.stax.EventFactoryImpl");

        searchView.clearFocus();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchData(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                return false;
            }
        });

        mainGoodsPermitAdapter = new MainGoodsPermitAdapter(this, list, this, this);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setAdapter(mainGoodsPermitAdapter);
        mainGoodsPermitAdapter.setDialog(new MainGoodsPermitAdapter.Dialog() {
            @Override
            public void onClick(int pos) {
                final CharSequence[] dialogItem = {"Edit", "Delete"};
                AlertDialog.Builder builder = new AlertDialog.Builder(UtamaDataBarang.this);
                View layout = getLayoutInflater().inflate(R.layout.edit_delete, null);
                Button btnEdit = layout.findViewById(R.id.btn_edt);
                Button btnDelete = layout.findViewById(R.id.btn_dlt);

                btnEdit.setOnClickListener(view1 -> {
                    filterCode = 1;
                    Intent intentEdit = new Intent(getApplicationContext(), EditGoodsPermitActivity.class);
                    intentEdit.putExtra("MAIN_EDIT_GOODS_PERMIT", list.get(pos));
                    startActivity(intentEdit);
                    dialog.dismiss();
                });
                btnDelete.setOnClickListener(view1 -> {
                    filterCode = 0;
                    new SweetAlertDialog(UtamaDataBarang.this, SweetAlertDialog.WARNING_TYPE)
                            .setTitleText("Warning!!!")
                            .setContentText("Are you sure want to delete this data ?")
                            .setConfirmText("OK")
                            .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                @Override
                                public void onClick(SweetAlertDialog sDialog) {
                                    try{
                                        deleteData(list.get(pos).getId());
                                        sDialog.dismissWithAnimation();
                                        StyleableToast.makeText(getApplicationContext(), "Delete Successfully!!!", Toast.LENGTH_SHORT, R.style.result).show();
                                    } catch (Exception e) {
                                        Log.e("error",e.getMessage());
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
                builder.setView(layout);
                dialog = builder.create();
                dialog.show();
            }
        });

        filter(filterCode);
        mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                filter(filterCode);
                mSwipeRefreshLayout.setRefreshing(false);
            }
        });

        System.setProperty("org.apache.poi.javax.xml.stream.XMLInputFactory", "com.fasterxml.aalto.stax.InputFactoryImpl");
        System.setProperty("org.apache.poi.javax.xml.stream.XMLOutputFactory", "com.fasterxml.aalto.stax.OutputFactoryImpl");
        System.setProperty("org.apache.poi.javax.xml.stream.XMLEventFactory", "com.fasterxml.aalto.stax.EventFactoryImpl");

        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                animateFab();
            }
        });

        fab1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (Build.VERSION.SDK_INT > Build.VERSION_CODES.M) {
                    if (getApplicationContext().checkSelfPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE) == PackageManager.PERMISSION_DENIED) {
                        String[] permissions = {Manifest.permission.WRITE_EXTERNAL_STORAGE};
                        requestPermissions(permissions, 1);
                    } else {
                        importData();
                    }
                } else {
                    importData();
                }
            }
        });
        fab2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String foldername = "Import Excel";
                Intent intent = new Intent(Intent.ACTION_PICK);
                intent.setDataAndType(Uri.parse(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + foldername), "*/*");
                startActivity(intent);
            }
        });

        filter.setOnClickListener(view -> {
            AlertDialog.Builder builder = new AlertDialog.Builder(UtamaDataBarang.this);
            View layout = getLayoutInflater().inflate(R.layout.filter_dialog, null);
            Button btnAscending = layout.findViewById(R.id.btn_asc);
            Button btnDescending = layout.findViewById(R.id.btn_desc);

            btnDescending.setOnClickListener(view1 -> {
                filterCode = 1;
                filter(filterCode);
                dialog.dismiss();
            });
            btnAscending.setOnClickListener(view1 -> {
                filterCode = 0;
                filter(filterCode);
                dialog.dismiss();
            });
            builder.setView(layout);
            dialog = builder.create();
            dialog.show();
        });
    }

    private void filter(int code) {
        if (code == 0) {
            showAllDataDesc();
        } else if (code == 1) {
            showAllDataAsc();
        }
    }

    private void animateFab(){
        if (isOpen){
            fab.startAnimation(rotateBackward);
            fab1.startAnimation(fabClose);
            fab2.startAnimation(fabClose);
            fab1.setClickable(false);
            fab2.setClickable(false);
            isOpen=false;
        }
        else{
            fab.startAnimation(rotateForward);
            fab1.startAnimation(fabOpen);
            fab2.startAnimation(fabOpen);
            fab1.setClickable(true);
            fab2.setClickable(true);
            isOpen=true;
        }
    }

    private void deleteData(String id) {

    }
    private void importData(){
        if(list.size()>0){
            createXlFile();
        } else {
            StyleableToast.makeText(getApplicationContext(),"List Are Empty!", Toast.LENGTH_SHORT,R.style.warning).show();
        }
    }

    private void createXlFile() {
        Workbook wb = new HSSFWorkbook();
        Cell cell = null;

        Sheet sheet = null;
        sheet = wb.createSheet("Demo Excel Sheet");

        Row row = sheet.createRow(0);

        cell = row.createCell(0);
        cell.setCellValue("Id");

        cell = row.createCell(1);
        cell.setCellValue("Name");

        cell = row.createCell(2);
        cell.setCellValue("Phone");

        cell = row.createCell(3);
        cell.setCellValue("PIC");

        cell = row.createCell(4);
        cell.setCellValue("Division");

        cell = row.createCell(5);
        cell.setCellValue("Department");

        cell = row.createCell(6);
        cell.setCellValue("Goods Name");

        cell = row.createCell(7);
        cell.setCellValue("Type of Goods");

        cell = row.createCell(8);
        cell.setCellValue("Picture");

        cell = row.createCell(9);
        cell.setCellValue("Date");

        cell = row.createCell(10);
        cell.setCellValue("Device");

        cell = row.createCell(11);
        cell.setCellValue("Latitude");

        cell = row.createCell(12);
        cell.setCellValue("Longitude");

        cell = row.createCell(13);
        cell.setCellValue("Location");

        cell = row.createCell(14);
        cell.setCellValue("Status");

        sheet.setColumnWidth(0, (30 * 200));
        sheet.setColumnWidth(1, (30 * 200));
        sheet.setColumnWidth(2, (30 * 200));
        sheet.setColumnWidth(3, (30 * 200));
        sheet.setColumnWidth(4, (30 * 200));
        sheet.setColumnWidth(5, (30 * 200));
        sheet.setColumnWidth(6, (30 * 200));
        sheet.setColumnWidth(7, (30 * 200));
        sheet.setColumnWidth(8, (30 * 200));
        sheet.setColumnWidth(9, (30 * 200));
        sheet.setColumnWidth(10, (30 * 200));
        sheet.setColumnWidth(11, (30 * 200));
        sheet.setColumnWidth(12, (30 * 200));
        sheet.setColumnWidth(13, (30 * 200));
        sheet.setColumnWidth(14, (30 * 200));

        for (int i = 0; i < list.size(); i++) {
            Row row1 = sheet.createRow(i + 1);

            cell = row1.createCell(0);
            cell.setCellValue(list.get(i).getId());

            cell = row1.createCell(1);
            cell.setCellValue((list.get(i).getName()));

            cell = row1.createCell(2);
            cell.setCellValue(list.get(i).getPhone());

            cell = row1.createCell(3);
            cell.setCellValue(list.get(i).getPic());

            cell = row1.createCell(4);
            cell.setCellValue(list.get(i).getDivision());

            cell = row1.createCell(5);
            cell.setCellValue(list.get(i).getDepartment());

            cell = row1.createCell(6);
            cell.setCellValue(list.get(i).getGoods_name());

            cell = row1.createCell(7);
            cell.setCellValue(list.get(i).getType());

            cell = row1.createCell(8);
            cell.setCellValue(list.get(i).getImg());

            cell = row1.createCell(9);
            cell.setCellValue(list.get(i).getDate());

            cell = row1.createCell(10);
            cell.setCellValue(list.get(i).getDevice());

            cell = row1.createCell(11);
            cell.setCellValue(list.get(i).getLatitude());

            cell = row1.createCell(12);
            cell.setCellValue(list.get(i).getLongitude());

            cell = row1.createCell(13);
            cell.setCellValue(list.get(i).getLocation());

            cell = row1.createCell(14);
            cell.setCellValue(list.get(i).getStatus());

        }

        String folderName = "Import Excel";
        String fileName = "Goods Permission_" + System.currentTimeMillis() + ".xls";
        String path = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + folderName + File.separator + fileName;

        File file = new File(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + folderName);
        if (!file.exists()) {
            file.mkdirs();
        }

        FileOutputStream outputStream = null;

        try {
            outputStream = new FileOutputStream(path);
            wb.write(outputStream);
            // ShareViaEmail(file.getParentFile().getName(),file.getName());
            StyleableToast.makeText(getApplicationContext(),"Excel Created in " + path, Toast.LENGTH_SHORT,R.style.logsuccess).show();
        } catch (IOException e) {
            e.printStackTrace();

            Toast.makeText(getApplicationContext(), e.toString(), Toast.LENGTH_LONG).show();
            try {
                outputStream.close();
            } catch (Exception ex) {
                ex.printStackTrace();

            }
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == 1 && grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            importData();
        } else {
            StyleableToast.makeText(getApplicationContext(),"Permission Denied!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
        }
    }

    private void deleteData(String id, String urlImage) {
        progressDialog.setTitle("Loading");
        progressDialog.setMessage("Deleting data...");
        progressDialog.show();
        db.collection("goods_permit").document(id)
                .delete()
                .addOnCompleteListener(new OnCompleteListener<Void>() {
                    @Override
                    public void onComplete(@NonNull Task<Void> task) {
                        if (!task.isSuccessful()){
                            Toast.makeText(getApplicationContext(), "Data gagal di hapus!", Toast.LENGTH_SHORT).show();
                        } else {
                            FirebaseStorage.getInstance().getReferenceFromUrl(urlImage).delete().addOnCompleteListener(new OnCompleteListener<Void>() {
                                @Override
                                public void onComplete(@NonNull Task<Void> task) {
                                    Toast.makeText(UtamaDataBarang.this, "Data berhasil dihapus", Toast.LENGTH_SHORT).show();
                                }
                            });
                        }
                        progressDialog.dismiss();
                        showAllDataDesc();
                    }
                });
    }

    private void showAllDataDesc() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog2);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("goods_permit")
                .orderBy("date", Query.Direction.DESCENDING)
                .addSnapshotListener(new EventListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                        list.clear();
                        if (value != null) {
                            for (QueryDocumentSnapshot  document : value) {
                                Barang barang = new Barang(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("phone"),
                                        document.getString("pic"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("goods_name"),
                                        document.getString("type"),
                                        document.getString("img"),
                                        document.getString("date"),
                                        document.getString("device"),
                                        document.getString("latitude"),
                                        document.getString("longitude"),
                                        document.getString("location"),
                                        document.getString("status")
                                );
                                list.add(barang);
                            }
                            mainGoodsPermitAdapter.notifyDataSetChanged();
                        } else {
                            StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                        progressDialog.dismiss();
                    }
                });
    }

    private void showAllDataAsc() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog2);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("goods_permit")
                .orderBy("date", Query.Direction.ASCENDING)
                .addSnapshotListener(new EventListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onEvent(@Nullable QuerySnapshot value, @Nullable FirebaseFirestoreException error) {
                        list.clear();
                        if (value != null) {
                            for (QueryDocumentSnapshot  document : value) {
                                Barang barang = new Barang(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("phone"),
                                        document.getString("pic"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("goods_name"),
                                        document.getString("type"),
                                        document.getString("img"),
                                        document.getString("date"),
                                        document.getString("device"),
                                        document.getString("latitude"),
                                        document.getString("longitude"),
                                        document.getString("location"),
                                        document.getString("status")
                                );
                                list.add(barang);
                            }
                            mainGoodsPermitAdapter.notifyDataSetChanged();
                        } else {
                            StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                        progressDialog.dismiss();
                    }
                });
    }

    private void searchData(String name) {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("goods_permit")
                .whereEqualTo("goods_name", name)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                Barang barang = new Barang(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("phone"),
                                        document.getString("pic"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("goods_name"),
                                        document.getString("type"),
                                        document.getString("img"),
                                        document.getString("date"),
                                        document.getString("device"),
                                        document.getString("latitude"),
                                        document.getString("longitude"),
                                        document.getString("location"),
                                        document.getString("status")
                                );
                                list.add(barang);
                            }
                            mainGoodsPermitAdapter.notifyDataSetChanged();
                        } else {
                            StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                        progressDialog.dismiss();
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        StyleableToast.makeText(getApplicationContext(),"Data Not Found!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        progressDialog.dismiss();
                    }
                });
    }

    public void ClickLogo(View view){
        closeDrawer(drawerLayout);
    }

    public static void closeDrawer(DrawerLayout drawerLayout){
        if(drawerLayout.isDrawerOpen(GravityCompat.START)){
            drawerLayout.closeDrawer(GravityCompat.START);
        }
    }

    public void ClickMain(View view){
        AkunUtama.redirectActivity(this, AkunUtama.class);
    }

    public void ClickEmployee(View view){
        AkunUtama.redirectActivity(this, UtamaDataEmployee.class);
        finish();
    }

    public void ClickComelate(View view){
        AkunUtama.redirectActivity(this, Utama_Data_Cometoolate.class);
    }

    public void ClickCheckup(View view){ AkunUtama.redirectActivity(this, UtamaDataCheckup.class); }

    public void ClickSubcon(View view){ AkunUtama.redirectActivity(this, UtamaDataSubcon.class); }

    public void ClickParksub(View view){ AkunUtama.redirectActivity(this, UtamaDataParksub.class); }

    public void ClickGuest(View view){
        AkunUtama.redirectActivity(this, UtamaDataGuest.class);
    }

    public void ClickVisitor(View view){
        AkunUtama.redirectActivity(this, UtamaDataVisitor.class);
    }

    public void ClickGoods(View view){
        AkunUtama.redirectActivity(this, UtamaDataBarang.class);
    }

    public void ClickEdit(View view){
        AkunUtama.redirectActivity(this, AkunUtama.class);
    }

    public void ClickExit(View view){
        AkunUtama.exit(this);
    }

    public static void redirectActivity(Activity activity, Class aClass){
        Intent intent = new Intent(activity,aClass);

        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

        activity.startActivity(intent);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startActivity(new Intent(this, AkunUtama.class));
        finish();
    }

    public void onPermitClick(int position) {
        intent = new Intent(UtamaDataBarang.this, DetailBarangActivity.class);
        intent.putExtra("MAIN_GOODS_PERMIT", list.get(position));
        startActivity(intent);
    }



    @Override
    protected void onResume() {
        super.onResume();
        showAllDataDesc();
    }

    @Override
    public void onLongCLickListener(int pos) {
        final CharSequence[] dialogItem = {"Edit", "Delete"};
        AlertDialog.Builder builder = new AlertDialog.Builder(UtamaDataBarang.this);
        View layout = getLayoutInflater().inflate(R.layout.edit_delete, null);
        Button btnEdit = layout.findViewById(R.id.btn_edt);
        Button btnDelete = layout.findViewById(R.id.btn_dlt);

        btnEdit.setOnClickListener(view1 -> {
            filterCode = 1;
            Intent intentEdit = new Intent(getApplicationContext(), EditGoodsPermitActivity.class);
            intentEdit.putExtra("MAIN_EDIT_GOODS_PERMIT", list.get(pos));
            startActivity(intentEdit);
            dialog.dismiss();
        });
        btnDelete.setOnClickListener(view1 -> {
            filterCode = 0;
            new SweetAlertDialog(UtamaDataBarang.this, SweetAlertDialog.WARNING_TYPE)
                    .setTitleText("Warning!!!")
                    .setContentText("Are you sure want to delete this data ?")
                    .setConfirmText("OK")
                    .setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                        @Override
                        public void onClick(SweetAlertDialog sDialog) {
                            try{
                                deleteData(list.get(pos).getId());
                                sDialog.dismissWithAnimation();
                                StyleableToast.makeText(getApplicationContext(), "Delete Successfully!!!", Toast.LENGTH_SHORT, R.style.result).show();
                            } catch (Exception e) {
                                Log.e("error",e.getMessage());
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
        builder.setView(layout);
        dialog = builder.create();
        dialog.show();
    }


    private void filterList(String filter) {

    }
}