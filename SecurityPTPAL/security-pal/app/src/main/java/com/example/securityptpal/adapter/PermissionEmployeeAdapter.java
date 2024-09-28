package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.PermissionEmployee;

import java.util.List;

public class PermissionEmployeeAdapter extends RecyclerView.Adapter<PermissionEmployeeAdapter.ExitViewHolder> {
    private Context context;
    private List<PermissionEmployee> list;
    private OnPermitListener mOnPermitListener;

    public PermissionEmployeeAdapter(Context context, List<PermissionEmployee> list, OnPermitListener onPermitListener) {
        this.context = context;
        this.list = list;
        this.mOnPermitListener = onPermitListener;
    }

    @NonNull
    @Override
    public ExitViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_employee_permission, parent, false);
        return new ExitViewHolder(itemView, mOnPermitListener);
    }

    @Override
    public void onBindViewHolder(@NonNull ExitViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
        holder.nip.setText(list.get(position).getNip());
        holder.date.setText(list.get(position).getDate());
        if (list.get(position).getDivision_approval().equals("Accepted") && list.get(position).getCenter_approval().equals("Accepted")){
            holder.status.setText("Accepted");
            holder.status.setTextColor(holder.status.getResources().getColor(R.color.main_green_color));
        } else if (list.get(position).getDivision_approval().equals("Rejected") || list.get(position).getCenter_approval().equals("Rejected")) {
            holder.status.setText("Rejected");
            holder.status.setTextColor(holder.status.getResources().getColor(R.color.cardColorRed));
        } else {
            holder.status.setText("Pending");
            holder.status.setTextColor(holder.status.getResources().getColor(R.color.main_orange_color));
        }

    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    class ExitViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView name, nip, status, date;
        OnPermitListener onPermitListener;
        public ExitViewHolder(@NonNull View itemView, OnPermitListener onPermitListener) {
            super(itemView);
            name = itemView.findViewById(R.id.employee_name);
            nip = itemView.findViewById(R.id.employee_nip);
            status = itemView.findViewById(R.id.permission_status);
            date = itemView.findViewById(R.id.employee_date);
            this.onPermitListener = onPermitListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onPermitListener.onPermitClick(getAdapterPosition());
        }
    }
}
