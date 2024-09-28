package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.CheckUp;

import java.util.List;

public class CheckupAdapter extends RecyclerView.Adapter<CheckupAdapter.CheckupViewHolder>{

    private Context context;
    private List<CheckUp> list;
    private CheckupAdapter.OnCheckupListener mOnCheckupListener;

    public CheckupAdapter(Context context, List<CheckUp> list, CheckupAdapter.OnCheckupListener mOnCheckupListener) {
        this.context = context;
        this.list = list;
        this.mOnCheckupListener = mOnCheckupListener;
    }

    @NonNull
    @Override
    public CheckupAdapter.CheckupViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_checkup_list, parent, false);
        return new CheckupAdapter.CheckupViewHolder(itemView, mOnCheckupListener);
    }

    @Override
    public void onBindViewHolder(@NonNull CheckupAdapter.CheckupViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
        holder.nip.setText(list.get(position).getNip());
        holder.date.setText(list.get(position).getDate());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class CheckupViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView name, nip, date;
        CheckupAdapter.OnCheckupListener onCheckupListener;
        public CheckupViewHolder(@NonNull View itemView, CheckupAdapter.OnCheckupListener onCheckupListener) {
            super(itemView);
            name = itemView.findViewById(R.id.checkup_name);
            nip = itemView.findViewById(R.id.checkup_nip);
            date = itemView.findViewById(R.id.checkup_date);
            this.onCheckupListener = onCheckupListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onCheckupListener.onCheckupClick(getAdapterPosition());
        }
    }

    public interface OnCheckupListener {
        void onCheckupClick(int position);
    }
}
