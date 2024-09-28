package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.PermissionLate;

import java.util.List;

public class GuestAdapter extends RecyclerView.Adapter<GuestAdapter.GuestViewHolder>{
    private Context context;
    private List<Guest> list;
    private GuestAdapter.OnGuestListener mOnGuestListener;
    private OnPermitLongClick mOnPermitLongClick;

    public GuestAdapter(Context context, List<Guest> list, OnGuestListener mOnGuestListener, OnPermitLongClick mOnPermitLongClick) {
        this.context = context;
        this.list = list;
        this.mOnGuestListener = mOnGuestListener;
        this.mOnPermitLongClick = mOnPermitLongClick;
    }

    @NonNull
    @Override
    public GuestAdapter.GuestViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_guest_list, parent, false);
        return new GuestAdapter.GuestViewHolder(itemView, mOnGuestListener, mOnPermitLongClick);
    }

    @Override
    public void onBindViewHolder(@NonNull GuestAdapter.GuestViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
        holder.company.setText(list.get(position).getCompany());
        holder.date.setText(list.get(position).getDate());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class GuestViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener, View.OnLongClickListener {
        TextView name, company, date;
        GuestAdapter.OnGuestListener onGuestListener;
        OnPermitLongClick onPermitLongClick;
        public GuestViewHolder(@NonNull View itemView, OnGuestListener onGuestListener, OnPermitLongClick onPermitLongClick) {
            super(itemView);
            name = itemView.findViewById(R.id.guest_name);
            company = itemView.findViewById(R.id.guest_company);
            date = itemView.findViewById(R.id.guest_date);
            this.onGuestListener = onGuestListener;
            this.onPermitLongClick = onPermitLongClick;

            itemView.setOnClickListener(this);
            itemView.setOnLongClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onGuestListener.onGuestClick(getAdapterPosition());
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

    public interface OnGuestListener {
        void onGuestClick(int position);
    }
}
