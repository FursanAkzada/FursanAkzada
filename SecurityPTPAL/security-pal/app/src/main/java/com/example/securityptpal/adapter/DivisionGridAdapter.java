package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.Division;

import java.util.List;

public class DivisionGridAdapter extends RecyclerView.Adapter<DivisionGridAdapter.DivisionViewHolder>{

    private Context context;
    private List<Division> list;
    private OnDivisionGridListener mOnDivisionListener;

    public DivisionGridAdapter(Context context, List<Division> list, OnDivisionGridListener mOnDivisionListener) {
        this.context = context;
        this.list = list;
        this.mOnDivisionListener = mOnDivisionListener;
    }

    @NonNull
    @Override
    public DivisionViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.grid_division_list, parent, false);
        return new DivisionGridAdapter.DivisionViewHolder(itemView, mOnDivisionListener);
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
        OnDivisionGridListener onDivisionListener;
        public DivisionViewHolder(@NonNull View itemView, OnDivisionGridListener onDivisionListener) {
            super(itemView);
            name = itemView.findViewById(R.id.grid_division_name);
            this.onDivisionListener = onDivisionListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onDivisionListener.onDivisionClick(getAdapterPosition());
        }
    }

    public interface OnDivisionGridListener {
        void onDivisionClick(int position);
    }
}
