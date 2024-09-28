package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.Toast;

import com.example.securityptpal.adapter.CheckupAdapter;
import com.example.securityptpal.adapter.VisitorAdapter;
import com.example.securityptpal.model.CheckUp;
import com.example.securityptpal.model.Visitor;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;
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

public class CheckupPermitActivity extends AppCompatActivity implements CheckupAdapter.OnCheckupListener{
        private RecyclerView recyclerView;
        FirebaseFirestore db = FirebaseFirestore.getInstance();
        private List<CheckUp> list = new ArrayList<>();
        private CheckupAdapter checkupAdapter;
        private SearchView searchView;
        private Intent intent;
        private String EXTRA;
        String userID;
        FirebaseAuth mAuth = FirebaseAuth.getInstance();
        private ProgressDialog progressDialog;
        FloatingActionButton fab, fab1, fab2;
        Animation fabOpen, fabClose, rotateForward, rotateBackward;
        ImageView btnFilter;
        int filterCode = 0;
        AlertDialog dialog;
    SwipeRefreshLayout mSwipeRefreshLayout;
        boolean isOpen = false;

        @Override
        protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_checkup_permit);
            recyclerView = findViewById(R.id.rv_checkup_permission);
            searchView = findViewById(R.id.search_checkup_permit);
            btnFilter = findViewById(R.id.div_filter_checkup);
            mSwipeRefreshLayout = findViewById(R.id.refresh_div_cu_permit);
            progressDialog = new ProgressDialog(CheckupPermitActivity.this);
            progressDialog.show();
            progressDialog.setContentView(R.layout.progress_dialog1);
            progressDialog.getWindow().setBackgroundDrawableResource(
                    android.R.color.transparent
            );

            fab = (FloatingActionButton) findViewById(R.id.fab);
            fab1 = (FloatingActionButton) findViewById(R.id.fab1);
            fab2 = (FloatingActionButton) findViewById(R.id.fab2);

            fabOpen = AnimationUtils.loadAnimation(this, R.anim.fab_open);
            fabClose = AnimationUtils.loadAnimation(this, R.anim.fab_close);

            rotateForward = AnimationUtils.loadAnimation(this, R.anim.rotate_forward);
            rotateBackward = AnimationUtils.loadAnimation(this, R.anim.rotate_backward);

            searchView.clearFocus();
            searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
                @Override
                public boolean onQueryTextSubmit(String query) {
                    searchData(query, EXTRA);
                    return false;
                }

                @Override
                public boolean onQueryTextChange(String newText) {
//                    showDataDivision(EXTRA);
                    return false;
                }
            });

            mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
                @Override
                public void onRefresh() {
                    showAllDataDesc(EXTRA);
                    mSwipeRefreshLayout.setRefreshing(false);
                }
            });

            checkupAdapter = new CheckupAdapter(this, list, this);
            RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
            RecyclerView.ItemDecoration decoration = new DividerItemDecoration(this, DividerItemDecoration.VERTICAL);
            recyclerView.setLayoutManager(layoutManager);
            recyclerView.addItemDecoration(decoration);
            recyclerView.setAdapter(checkupAdapter);

            Bundle extras = getIntent().getExtras();
            EXTRA = extras.getString(Intent.EXTRA_TEXT);

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

            filter(filterCode);

            btnFilter.setOnClickListener(view -> {
            AlertDialog.Builder builder = new AlertDialog.Builder(CheckupPermitActivity.this);
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
            showAllDataDesc(EXTRA);
        } else if (code == 1) {
            showAllDataAsc(EXTRA);
        }
    }

    private void showAllDataDesc(String division) {
        db.collection("permission_checkup")
                .whereEqualTo("division", division)
                .orderBy("date", Query.Direction.DESCENDING)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                CheckUp checkUp = new CheckUp(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("nip"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("status"),
                                        document.getString("date"),
                                        document.getString("type"),
                                        document.getString("others"),
                                        document.getString("division_approval"),
                                        document.getString("center_approval")
                                );
                                list.add(checkUp);
                            }
                            checkupAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            Toast.makeText(CheckupPermitActivity.this, "data gagal dimuat"+task.getException(), Toast.LENGTH_SHORT).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(CheckupPermitActivity.this, "data tidak ditemukan"+e.getMessage(), Toast.LENGTH_SHORT).show();
                        progressDialog.hide();
                    }
                });
    }

    private void showAllDataAsc(String division) {
        db.collection("permission_checkup")
                .whereEqualTo("division", division)
                .orderBy("date", Query.Direction.ASCENDING)
                .get()
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onComplete(@NonNull Task<QuerySnapshot> task) {
                        list.clear();
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                CheckUp checkUp = new CheckUp(
                                        document.getId(),
                                        document.getString("name"),
                                        document.getString("nip"),
                                        document.getString("division"),
                                        document.getString("department"),
                                        document.getString("status"),
                                        document.getString("date"),
                                        document.getString("type"),
                                        document.getString("others"),
                                        document.getString("division_approval"),
                                        document.getString("center_approval")
                                );
                                list.add(checkUp);
                            }
                            checkupAdapter.notifyDataSetChanged();
                            progressDialog.hide();
                        } else {
                            Toast.makeText(CheckupPermitActivity.this, "data gagal dimuat", Toast.LENGTH_SHORT).show();
                            progressDialog.hide();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(CheckupPermitActivity.this, "data tidak ditemukan", Toast.LENGTH_SHORT).show();
                        progressDialog.hide();
                    }
                });
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

        private void showDataDivision(String division) {
            progressDialog.show();
            db.collection("permission_checkup")
                    .whereEqualTo("division", division)
                    .orderBy("date", Query.Direction.DESCENDING)
                    .get()
                    .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                        @SuppressLint("NotifyDataSetChanged")
                        @Override
                        public void onComplete(@NonNull Task<QuerySnapshot> task) {
                            list.clear();
                            if (task.isSuccessful()) {
                                for (QueryDocumentSnapshot document : task.getResult()) {
                                    CheckUp checkUp = new CheckUp(
                                            document.getId(),
                                            document.getString("name"),
                                            document.getString("nip"),
                                            document.getString("division"),
                                            document.getString("department"),
                                            document.getString("status"),
                                            document.getString("date"),
                                            document.getString("type"),
                                            document.getString("others"),
                                            document.getString("division_approval"),
                                            document.getString("center_approval")
                                    );
                                    list.add(checkUp);
                                }
                                checkupAdapter.notifyDataSetChanged();
                            } else {
                                StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                            }
                            progressDialog.dismiss();
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            Toast.makeText(CheckupPermitActivity.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
                            progressDialog.dismiss();
                        }
                    });
        }
        private void searchData(String nip, String division) {
            db.collection("permission_checkup")
                    .whereEqualTo("nip", nip)
                    .whereEqualTo("division", division)
                    .get()
                    .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                        @SuppressLint("NotifyDataSetChanged")
                        @Override
                        public void onComplete(@NonNull Task<QuerySnapshot> task) {
                            list.clear();
                            if (task.isSuccessful()) {
                                for (QueryDocumentSnapshot document : task.getResult()) {
                                    CheckUp checkUp = new CheckUp(
                                            document.getId(),
                                            document.getString("name"),
                                            document.getString("nip"),
                                            document.getString("division"),
                                            document.getString("department"),
                                            document.getString("status"),
                                            document.getString("date"),
                                            document.getString("type"),
                                            document.getString("others"),
                                            document.getString("division_approval"),
                                            document.getString("center_approval")
                                    );
                                    list.add(checkUp);
                                }
                                checkupAdapter.notifyDataSetChanged();
                            } else {
                                StyleableToast.makeText(getApplicationContext(),"Load Data Failed!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                            }
                        }
                    })
                    .addOnFailureListener(new OnFailureListener() {
                        @Override
                        public void onFailure(@NonNull Exception e) {
                            StyleableToast.makeText(getApplicationContext(),"Data Not Found!", Toast.LENGTH_SHORT,R.style.resultfailed).show();
                        }
                    });
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
            sheet = wb.createSheet("Excel Permission CheckUp");

            Row row = sheet.createRow(0);

            cell = row.createCell(0);
            cell.setCellValue("Id");

            cell = row.createCell(1);
            cell.setCellValue("Name");

            cell = row.createCell(2);
            cell.setCellValue("NIP");

            cell = row.createCell(3);
            cell.setCellValue("Division");

            cell = row.createCell(4);
            cell.setCellValue("Department");

            cell = row.createCell(5);
            cell.setCellValue("Status");

            cell = row.createCell(6);
            cell.setCellValue("Date");

            cell = row.createCell(7);
            cell.setCellValue("Type");

            cell = row.createCell(8);
            cell.setCellValue("Others");

            cell = row.createCell(9);
            cell.setCellValue("Division Approval");

            cell = row.createCell(10);
            cell.setCellValue("Center Approval");

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

            for (int i = 0; i < list.size(); i++) {
                Row row1 = sheet.createRow(i + 1);

                cell = row1.createCell(0);
                cell.setCellValue(list.get(i).getId());

                cell = row1.createCell(1);
                cell.setCellValue((list.get(i).getName()));

                cell = row1.createCell(2);
                cell.setCellValue(list.get(i).getNip());

                cell = row1.createCell(3);
                cell.setCellValue(list.get(i).getDivision());

                cell = row1.createCell(4);
                cell.setCellValue(list.get(i).getDepartment());

                cell = row1.createCell(5);
                cell.setCellValue(list.get(i).getStatus());

                cell = row1.createCell(6);
                cell.setCellValue(list.get(i).getDate());

                cell = row1.createCell(7);
                cell.setCellValue(list.get(i).getType());

                cell = row1.createCell(8);
                cell.setCellValue(list.get(i).getOthers());

                cell = row1.createCell(9);
                cell.setCellValue(list.get(i).getDivision_approval());

                cell = row1.createCell(10);
                cell.setCellValue(list.get(i).getCenter_approval());
            }

            String folderName = "Import Excel";
            String fileName = "Checkup Permission_"+ EXTRA + System.currentTimeMillis() + ".xls";
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
        protected void onStart() {
            super.onStart();
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

        @Override
        public void onCheckupClick(int position) {
            intent = new Intent(CheckupPermitActivity.this, DetailCheckupActivity.class);
            intent.putExtra("MAIN_CHECKUP_PERMIT", list.get(position));
            startActivity(intent);
        }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        list.clear();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }
}