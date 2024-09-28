package com.example.securityptpal;

import android.app.Activity;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebHistoryItem;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.afollestad.sectionedrecyclerview.SectionedRecyclerViewAdapter;
import com.google.android.material.tabs.TabLayout;

import java.util.ArrayList;
import java.util.HashMap;

public class MainAdapter2 extends SectionedRecyclerViewAdapter<MainAdapter2.ViewHolder> {
    Activity activity;
    ArrayList<String> sectionList;
    HashMap<String,ArrayList<String>> itemList = new HashMap<>();
    int selectedSection = -1;
    int selectedItem = -1;

    public MainAdapter2(Activity activity,ArrayList<String> sectionList
            ,HashMap<String,ArrayList<String>> itemList){
        this.activity = activity;
        this.sectionList = sectionList;
        this.itemList = itemList;
        notifyDataSetChanged();
    }
    @Override
    public int getSectionCount() {
        return sectionList.size();
    }

    @Override
    public int getItemCount(int section) {
        return itemList.get(sectionList.get(section)).size();
    }

    @Override
    public void onBindHeaderViewHolder(ViewHolder viewHolder, int i) {
        viewHolder.textView.setText((sectionList.get(i)));
    }

    @Override
    public void onBindViewHolder(ViewHolder viewHolder, int i, int i1, int i2) {
        String sItem = itemList.get(sectionList.get(i)).get(i1);
        viewHolder.textView.setText(sItem);
        viewHolder.textView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Toast.makeText(activity,sItem, Toast.LENGTH_SHORT).show();
                selectedSection = i;
                selectedItem = i1;
                notifyDataSetChanged();
            }
        });
        if (selectedSection == i && selectedItem == i1){
            viewHolder.textView.setBackground(ContextCompat.getDrawable(
                    activity,R.drawable.rectangle_fill
            ));
            viewHolder.textView.setTextColor(Color.WHITE);
        }else {
            viewHolder.textView.setBackground(ContextCompat.getDrawable(
                    activity,R.drawable.rectangle_outline
            ));
            viewHolder.textView.setTextColor(Color.BLACK);
        }
    }

    @Override
    public int getItemViewType(int section, int relativePosition, int absolutePosition) {
        if (section == 1){
            return 0;
        }
        return super.getItemViewType(section, relativePosition, absolutePosition);
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        int layout;

        if (viewType == VIEW_TYPE_HEADER){
            layout = R.layout.item_header;
        }else {
            layout = R.layout.item_slot;
        }
        View view = LayoutInflater.from(parent.getContext())
                .inflate(layout,parent,false);
        return new ViewHolder(view);
    }

    public class ViewHolder extends RecyclerView.ViewHolder {
        TextView textView;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            textView = itemView.findViewById(R.id.text_view);
        }
    }
}
