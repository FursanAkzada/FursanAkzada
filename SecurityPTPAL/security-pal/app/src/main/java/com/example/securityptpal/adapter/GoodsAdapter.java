package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.Barang;
import com.example.securityptpal.model.Guest;

import java.util.List;

public class GoodsAdapter extends RecyclerView.Adapter<GoodsAdapter.GoodsViewHolder>{
    private Context context;
    private List<Barang> list;
    private GoodsAdapter.OnGoodsListener mOnGoodsListener;

    public GoodsAdapter(Context context, List<Barang> list, GoodsAdapter.OnGoodsListener mOnGoodsListener) {
        this.context = context;
        this.list = list;
        this.mOnGoodsListener = mOnGoodsListener;
    }

    @NonNull
    @Override
    public GoodsAdapter.GoodsViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_goods_list, parent, false);
        return new GoodsAdapter.GoodsViewHolder(itemView, mOnGoodsListener);
    }

    @Override
    public void onBindViewHolder(@NonNull GoodsViewHolder holder, int position) {
        holder.goodsname.setText(list.get(position).getGoods_name());
        holder.name.setText(list.get(position).getName());
        holder.date.setText(list.get(position).getDate());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public class GoodsViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView goodsname, name, date;
        GoodsAdapter.OnGoodsListener onGoodsListener;
        public GoodsViewHolder(@NonNull View itemView, GoodsAdapter.OnGoodsListener onGoodsListener) {
            super(itemView);
            goodsname = itemView.findViewById(R.id.goods_name);
            name = itemView.findViewById(R.id.goods_username);
            date = itemView.findViewById(R.id.goods_date);
            this.onGoodsListener = onGoodsListener;

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onGoodsListener.onGoodsClick(getAdapterPosition());
        }
    }

    public interface OnGoodsListener {
        void onGoodsClick(int position);
    }
}
