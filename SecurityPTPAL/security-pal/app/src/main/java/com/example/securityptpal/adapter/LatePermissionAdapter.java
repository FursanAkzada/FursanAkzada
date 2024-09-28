package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.PermissionLate;

import java.util.List;

public class LatePermissionAdapter extends RecyclerView.Adapter<LatePermissionAdapter.LateViewHolder> {

    private Context context;
    private List<PermissionLate> list;
    private LatePermissionAdapter.OnLateListener mOnLateListener;

    public LatePermissionAdapter(Context context, List<PermissionLate> list, OnLateListener mOnLateListener) {
        this.context = context;
        this.list = list;
        this.mOnLateListener = mOnLateListener;
    }

    @NonNull
    @Override
    public LateViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_late_permission, parent, false);
        return new LateViewHolder(itemView, mOnLateListener);
    }

    @Override
    public void onBindViewHolder(@NonNull LateViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
        holder.nip.setText(list.get(position).getNip());
        holder.date.setText(list.get(position).getDate());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    class LateViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView name, nip, date;
        OnLateListener onLateListener;
        public LateViewHolder(@NonNull View itemView, OnLateListener onLateListener) {
            super(itemView);
            name = itemView.findViewById(R.id.late_employee_name);
            nip = itemView.findViewById(R.id.late_employee_nip);
            date = itemView.findViewById(R.id.late_employee_date);
            this.onLateListener = onLateListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onLateListener.onLateClick(getAdapterPosition());
        }
    }

    public interface OnLateListener {
        void onLateClick(int position);
    }
}
