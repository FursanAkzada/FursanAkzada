package com.example.securityptpal.adapter;

import android.annotation.SuppressLint;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.main.MainDivisionActivity;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.firestore.FieldValue;
import com.google.firebase.firestore.FirebaseFirestore;
import com.muddzdev.styleabletoast.StyleableToast;

import java.util.List;

public class DepartmentAdapter extends RecyclerView.Adapter<DepartmentAdapter.DepartmentViewHolder>{

    List<String> department;
    FirebaseFirestore db = FirebaseFirestore.getInstance();
    String id;

    public DepartmentAdapter(List<String> department, String id) {
        this.department = department;
        this.id = id;
    }

    @NonNull
    @Override
    public DepartmentViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_department_list, parent, false);
        return new DepartmentAdapter.DepartmentViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(@NonNull DepartmentViewHolder holder, int position) {
        holder.name.setText(department.get(position));
    }

    @Override
    public int getItemCount() {
        return department.size();
    }

    public class DepartmentViewHolder extends RecyclerView.ViewHolder {
        TextView name;
        ImageView delete;
        public DepartmentViewHolder(@NonNull View itemView) {
            super(itemView);
            name = itemView.findViewById(R.id.department_name);
            delete = itemView.findViewById(R.id.department_delete);
            delete.setOnClickListener(view -> {
                db.collection("division").document(id)
                        .update("department", FieldValue.arrayRemove(department.get(getAdapterPosition())))
                        .addOnSuccessListener(new OnSuccessListener<Void>() {
                            @SuppressLint("NotifyDataSetChanged")
                            @Override
                            public void onSuccess(Void unused) {
                                StyleableToast.makeText(itemView.getContext(), "Delete Succesfully !!", Toast.LENGTH_SHORT,R.style.logsuccess).show();
                                department.remove(getAdapterPosition());
                                notifyDataSetChanged();
                            }
                        })
                        .addOnFailureListener(new OnFailureListener() {
                            @Override
                            public void onFailure(@NonNull Exception e) {
                                Toast.makeText(itemView.getContext(), e.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
                            }
                        });
            });
        }
    }
}
