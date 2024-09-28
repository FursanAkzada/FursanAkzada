package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.CheckUp;
import com.example.securityptpal.model.Visitor;

import java.util.List;

public class MainCheckupPermitAdapter extends RecyclerView.Adapter<MainCheckupPermitAdapter.MainPermitViewHolder>{
    private Context context;
    private List<CheckUp> list;
    private OnPermitListener mOnPermitListener;
    private MainCheckupPermitAdapter.Dialog dialog;
    private OnPermitLongClick mOnPermitLongClick;

    public interface Dialog {
        void onClick(int pos);
    }

    public void setDialog (MainCheckupPermitAdapter.Dialog dialog) {
        this.dialog = dialog;
    }

    public MainCheckupPermitAdapter(Context context, List<CheckUp> list, OnPermitListener onPermitListener, OnPermitLongClick mOnPermitLongClick) {
        this.context = context;
        this.list = list;
        this.mOnPermitListener = onPermitListener;
        this.mOnPermitLongClick = mOnPermitLongClick;
    }

    @NonNull
    @Override
    public MainPermitViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_main_checkup_permission, parent, false);
        return new MainCheckupPermitAdapter.MainPermitViewHolder(itemView, mOnPermitListener, mOnPermitLongClick);
    }

    @Override
    public void onBindViewHolder(@NonNull MainCheckupPermitAdapter.MainPermitViewHolder holder, int position) {
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

    public class MainPermitViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener, View.OnLongClickListener {
        TextView name, nip, status, date;
        ImageView options;
        OnPermitListener onPermitListener;
        OnPermitLongClick onPermitLongClick;
        public MainPermitViewHolder(@NonNull View itemView, OnPermitListener onPermitListener, OnPermitLongClick onPermitLongClick) {
            super(itemView);
            name = itemView.findViewById(R.id.main_checkup_name);
            nip = itemView.findViewById(R.id.main_checkup_nip);
            status = itemView.findViewById(R.id.main_checkup_status);
            date = itemView.findViewById(R.id.main_checkup_date);
            this.onPermitListener = onPermitListener;
            options = itemView.findViewById(R.id.main_checkup_settings);
            options.setOnClickListener(view -> {
                if (dialog != null){
                    dialog.onClick(getLayoutPosition());
                }
            });
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
