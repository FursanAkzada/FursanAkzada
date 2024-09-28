package com.example.securityptpal.adapter;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.IdcardDetailSubcon;
import com.example.securityptpal.R;
import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Subcon;

import java.util.List;

public class SubconAdapter extends RecyclerView.Adapter<SubconAdapter.SubconViewHolder>{
    private Context context;
    private List<Subcon> list;
    private OnPermitListener mOnPermitListener;
    private OnPermitLongClick mOnPermitLongClick;

    public SubconAdapter(Context context, List<Subcon> list, OnPermitListener mOnPermitListener, OnPermitLongClick mOnPermitLongClick) {
        this.context = context;
        this.list = list;
        this.mOnPermitListener = mOnPermitListener;
        this.mOnPermitLongClick = mOnPermitLongClick;
    }

    @NonNull
    @Override
    public SubconViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_subcon_list, parent, false);
        return new SubconAdapter.SubconViewHolder(itemView, mOnPermitListener, mOnPermitLongClick);
    }

    @Override
    public void onBindViewHolder(@NonNull SubconViewHolder holder, int position) {
        holder.name.setText(list.get(position).getCompany());
        holder.div.setText(list.get(position).getDivision());
        holder.dep.setText(list.get(position).getDepartment());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class SubconViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener, View.OnLongClickListener{
        TextView name, div, dep;
        OnPermitListener onPermitListener;
        OnPermitLongClick onPermitLongClick;
        public SubconViewHolder(@NonNull View itemView, OnPermitListener onPermitListener, OnPermitLongClick onPermitLongClick) {
            super(itemView);
            name = itemView.findViewById(R.id.company_subcon);
            div = itemView.findViewById(R.id.division);
            dep = itemView.findViewById(R.id.department);

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
