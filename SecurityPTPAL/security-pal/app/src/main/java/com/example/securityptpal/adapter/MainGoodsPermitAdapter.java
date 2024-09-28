package com.example.securityptpal.adapter;

import static android.content.ContentValues.TAG;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.UtamaDataBarang;
import com.example.securityptpal.main.EditGoodsPermitActivity;
import com.example.securityptpal.model.Barang;

import java.util.List;

public class MainGoodsPermitAdapter extends RecyclerView.Adapter<MainGoodsPermitAdapter.MainGoodsViewHolder>{
    private Context context;
    private List<Barang> list;
    private OnPermitListener mOnPermitListener;
    private Dialog dialog;
    private OnPermitLongClick mOnPermitLongClick;

    public interface Dialog {
        void onClick(int pos);
    }

    public void setDialog (MainGoodsPermitAdapter.Dialog dialog) {
        this.dialog = dialog;
    }

    public MainGoodsPermitAdapter(Context context, List<Barang> list, OnPermitListener mOnPermitListener, OnPermitLongClick mOnPermitLongClick) {
        this.context = context;
        this.list = list;
        this.mOnPermitListener = mOnPermitListener;
        this.mOnPermitLongClick = mOnPermitLongClick;
    }

    @NonNull
    @Override
    public MainGoodsViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_main_goods_permission, parent, false);
        return new MainGoodsPermitAdapter.MainGoodsViewHolder(itemView, mOnPermitListener, mOnPermitLongClick);
    }

    @Override
    public void onBindViewHolder(@NonNull MainGoodsViewHolder holder, int position) {
        holder.name.setText(list.get(position).getGoods_name());
        holder.userName.setText(list.get(position).getName());
        holder.date.setText(list.get(position).getDate());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class MainGoodsViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener, View.OnLongClickListener {
        TextView name, userName, date;
        ImageView options;
        OnPermitListener onPermitListener;
        OnPermitLongClick onPermitLongClick;
        public MainGoodsViewHolder(@NonNull View itemView, OnPermitListener onPermitListener, OnPermitLongClick onPermitLongClick) {
            super(itemView);
            name = itemView.findViewById(R.id.main_goods_name);
            userName = itemView.findViewById(R.id.main_goods_user_name);
            date = itemView.findViewById(R.id.main_goods_date);
            options = itemView.findViewById(R.id.main_goods_settings);
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
