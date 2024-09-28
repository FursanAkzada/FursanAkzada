package com.example.securityptpal.adapter;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.Division;
import com.example.securityptpal.model.PermissionLate;

import java.util.List;

public class DivisionAdapter extends RecyclerView.Adapter<DivisionAdapter.DivisionViewHolder> {

    private Context context;
    private List<Division> list;
    private Dialog dialog;
    private DivisionAdapter.OnDivisionListener mOnDivisionListener;

    public interface Dialog {
        void onClick(int pos);
    }

    public void setDialog (Dialog dialog) {
        this.dialog = dialog;
    }

    public DivisionAdapter(Context context, List<Division> list, OnDivisionListener mOnDivisionListener) {
        this.context = context;
        this.list = list;
        this.mOnDivisionListener = mOnDivisionListener;
    }

    @NonNull
    @Override
    public DivisionViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_division_list, parent, false);
        return new DivisionViewHolder(itemView, mOnDivisionListener);
    }

    @Override
    public void onBindViewHolder(@NonNull DivisionViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class DivisionViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView name;
        ImageView settings;
        OnDivisionListener onDivisionListener;
        public DivisionViewHolder(@NonNull View itemView, OnDivisionListener onDivisionListener) {
            super(itemView);
            name = itemView.findViewById(R.id.division_name);
            settings = itemView.findViewById(R.id.division_settings);
            settings.setOnClickListener(view -> {
                if (dialog != null){
                    dialog.onClick(getLayoutPosition());
                }
            });
            this.onDivisionListener = onDivisionListener;
            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onDivisionListener.onDivisionClick(getAdapterPosition());
        }
    }

    public interface OnDivisionListener {
        void onDivisionClick(int position);
    }
}
