package com.example.securityptpal.adapter;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Adapter;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.constraintlayout.widget.ConstraintLayout;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.DetailSubconActivity;
import com.example.securityptpal.IdcardDetailSubcon;
import com.example.securityptpal.MonitoringSubcontractor;
import com.example.securityptpal.R;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Subcon;

import java.util.ArrayList;
import java.util.List;

public class SubconEmployeeAdapter extends RecyclerView.Adapter<SubconEmployeeAdapter.SubconEmployeeViewHolder>{
    private Context context;
    private List<EmployeeSubcon> list;
    Subcon subcon;
    private OnPermitListener mOnPermitListener;
    private OnPermitLongClick mOnPermitLongClick;

    public SubconEmployeeAdapter(Context context, List<EmployeeSubcon> list, OnPermitListener mOnPermitListener, OnPermitLongClick mOnPermitLongClick) {
        this.context = context;
        this.list = list;
        this.mOnPermitListener = mOnPermitListener;
        this.mOnPermitLongClick = mOnPermitLongClick;
    }

    @NonNull
    @Override
    public SubconEmployeeViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_subcon_employee, parent, false);
        return new SubconEmployeeAdapter.SubconEmployeeViewHolder(itemView, mOnPermitListener, mOnPermitLongClick);
    }

    @Override
    public void onBindViewHolder(@NonNull SubconEmployeeViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
        holder.id.setText(list.get(position).getId());
        holder.period.setText(list.get(position).getPeriode());
//        holder.age.setText(list.get(position).getName());
//        holder.address.setText(list.get(position).getNip());
//        holder.phone.setText(list.get(position).getName());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }


    public class SubconEmployeeViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener, View.OnLongClickListener {
        TextView name, id, period;
        OnPermitListener onPermitListener;
        OnPermitLongClick onPermitLongClick;
        public SubconEmployeeViewHolder(@NonNull View itemView, OnPermitListener onPermitListener, OnPermitLongClick onPermitLongClick) {
            super(itemView);
            name = itemView.findViewById(R.id.subcon_employee_name);
            id = itemView.findViewById(R.id.subcon_employee_id);
            period = itemView.findViewById(R.id.subcon_employee_period);

            this.onPermitListener = onPermitListener;
            this.onPermitLongClick = onPermitLongClick;
            itemView.setOnClickListener(this);
            itemView.setOnLongClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onPermitListener.onPermitClick(getAdapterPosition());
        }

        @Override
        public boolean onLongClick(View view) {
            if (onPermitLongClick != null) {
                int pos = getAdapterPosition();
                if (pos != RecyclerView.NO_POSITION){
                    onPermitLongClick.onLongCLickListener(pos);
                }
            }
            return true;
        }
    }
}
