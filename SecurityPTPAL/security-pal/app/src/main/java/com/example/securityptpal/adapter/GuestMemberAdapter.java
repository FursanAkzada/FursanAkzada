package com.example.securityptpal.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.securityptpal.R;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Guest;
import com.example.securityptpal.model.MemberGuest;
import com.example.securityptpal.model.Subcon;

import java.util.List;

public class GuestMemberAdapter extends RecyclerView.Adapter<GuestMemberAdapter.MemberGuestViewHolder>{
    private Context context;
    private List<MemberGuest> list;
    Guest guest;
    private OnPermitListener mOnPermitListener;

    public GuestMemberAdapter(Context context, List<MemberGuest> list, OnPermitListener mOnPermitListener) {
        this.context = context;
        this.list = list;
        this.mOnPermitListener = mOnPermitListener;
    }

    @NonNull
    @Override
    public MemberGuestViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_guest_member, parent, false);
        return new GuestMemberAdapter.MemberGuestViewHolder(itemView, mOnPermitListener);
    }

    @Override
    public void onBindViewHolder(@NonNull MemberGuestViewHolder holder, int position) {
        holder.name.setText(list.get(position).getName());
//        holder.age.setText(list.get(position).getName());
//        holder.address.setText(list.get(position).getNip());
//        holder.phone.setText(list.get(position).getName());
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

//    public class SubconEmployeeViewHolder extends RecyclerView.ViewHolder {
//        TextView name, nip, age, address, phone;
//        ConstraintLayout download;
//        public SubconEmployeeViewHolder(@NonNull View itemView) {
//            super(itemView);
//            name = itemView.findViewById(R.id.subcon_employee_name);
//            nip = itemView.findViewById(R.id.subcon_employee_nip);
//            age = itemView.findViewById(R.id.subcon_employee_age);
//            address = itemView.findViewById(R.id.subcon_employee_address);
//            phone = itemView.findViewById(R.id.subcon_employee_phone);
//            download = itemView.findViewById(R.id.downloadidcard);
//
//            download.setOnClickListener(new View.OnClickListener() {
//                @Override
//                public void onClick(View view) {
//                    Intent intent = new Intent(context, IdcardDetailSubcon.class);
//                    intent.putExtra("SUBCON_IDCARD", list.get(getAdapterPosition()));
//                    context.startActivity(intent);
//                }
//            });

    public class MemberGuestViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView name;
        OnPermitListener onPermitListener;
        public MemberGuestViewHolder(@NonNull View itemView, OnPermitListener onPermitListener) {
            super(itemView);
            name = itemView.findViewById(R.id.guest_member_name);

            this.onPermitListener = onPermitListener;
            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            onPermitListener.onPermitClick(getAdapterPosition());
        }
    }
}
