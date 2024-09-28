package com.example.securityptpal.division;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.DividerItemDecoration;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.Toast;

import com.example.securityptpal.LogoutAccount;
import com.example.securityptpal.R;
import com.example.securityptpal.adapter.DivisionAdapter;
import com.example.securityptpal.adapter.DivisionGridAdapter;
import com.example.securityptpal.main.MainDivisionActivity;
import com.example.securityptpal.model.Division;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.Query;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;

import java.util.ArrayList;
import java.util.List;

public class AkunDivisi extends AppCompatActivity implements DivisionGridAdapter.OnDivisionGridListener {

    public static final String EXTRA_MESSAGE_DESIGN = "Design";
    public static final String EXTRA_MESSAGE_IT = "Information Technology";
    private ImageView imgSignOut;
    private CardView cvDesignDiv, cvITDiv;
    private ProgressDialog progressDialog;
    private List<Division> list = new ArrayList<>();
    private DivisionGridAdapter divisionAdapter;
    private RecyclerView recyclerView;
    FirebaseFirestore db = FirebaseFirestore.getInstance();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_akun_divisi);
        imgSignOut = findViewById(R.id.sign_out_division_account);
        recyclerView = findViewById(R.id.rv_div);
        progressDialog = new ProgressDialog(AkunDivisi.this);

        divisionAdapter = new DivisionGridAdapter(this, list, this);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(this, 2, GridLayoutManager.VERTICAL, false);
        recyclerView.setLayoutManager(gridLayoutManager);
        recyclerView.setAdapter(divisionAdapter);

        showAllData();

        imgSignOut.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                LogoutAccount.logout(AkunDivisi.this);
            }
        });
    }

    private void showAllData() {
        progressDialog.show();
        progressDialog.setContentView(R.layout.progress_dialog1);
        progressDialog.getWindow().setBackgroundDrawableResource(
                android.R.color.transparent
        );
        db.collection("division")
                .orderBy("name", Query.Direction.ASCENDING)
                .get()
                .addOnSuccessListener(new OnSuccessListener<QuerySnapshot>() {
                    @SuppressLint("NotifyDataSetChanged")
                    @Override
                    public void onSuccess(QuerySnapshot queryDocumentSnapshots) {
                        list.clear();
                        for (QueryDocumentSnapshot documentSnapshot : queryDocumentSnapshots) {
                            Division mDivision = documentSnapshot.toObject(Division.class);

                            Division division = new Division(
                                    documentSnapshot.getId(),
                                    documentSnapshot.getString("name"),
                                    mDivision.getDepartment()
                            );
                            list.add(division);
                        }
                        divisionAdapter.notifyDataSetChanged();
                        progressDialog.dismiss();
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(AkunDivisi.this, e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
                        progressDialog.dismiss();
                    }
                });
    }

    @Override
    public void onDivisionClick(int position) {
        Intent intent = new Intent(AkunDivisi.this, EmployeePermitActivity.class);
        String EXTRA_MESSAGE = list.get(position).getName();
        intent.putExtra(Intent.EXTRA_TEXT, EXTRA_MESSAGE);
        startActivity(intent);
    }
}