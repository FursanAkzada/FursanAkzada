package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.Visitor;

import java.util.List;

public class VisitorAdapter extends RecyclerView.Adapter<VisitorAdapter.VisitorViewHolder>{
    private Context context;
    private List<Visitor> list;
    private VisitorAdapter.OnVisitorListener mOnVisitorListener;

    public VisitorAdapter(Context context, List<Visitor> list, VisitorAdapter.OnVisitorListener mOnVisitorListener) {
        this.context = context;
        this.list = list;
        this.mOnVisitorListener = mOnVisitorListener;
    }

    @NonNull
    @Override
    public VisitorAdapter.VisitorViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_visitor_list, parent, false);
        return new VisitorAdapter.VisitorViewHolder(itemView, mOnVisitorListener);
    }

    @Override
    public void onBindViewHolder(@NonNull VisitorAdapter.VisitorViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
        holder.company.setText(list.get(position).getCompany());
        holder.date.setText(list.get(position).getDate());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class VisitorViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView name, company, date;
        VisitorAdapter.OnVisitorListener onVisitorListener;
        public VisitorViewHolder(@NonNull View itemView, VisitorAdapter.OnVisitorListener onVisitorListener) {
            super(itemView);
            name = itemView.findViewById(R.id.visitor_name);
            company = itemView.findViewById(R.id.visitor_company);
            date = itemView.findViewById(R.id.visitor_date);
            this.onVisitorListener = onVisitorListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onVisitorListener.onVisitorClick(getAdapterPosition());
        }
    }

    public interface OnVisitorListener {
        void onVisitorClick(int position);
    }
}
