package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import java.util.ArrayList;
import java.util.HashMap;

public class FilterPage extends AppCompatActivity {
    RecyclerView recyclerView;
    ArrayList<String> sectionList = new ArrayList<>();
    HashMap<String,ArrayList<String>> itemList = new HashMap<>();
    MainAdapter2 adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_filter_page);
        recyclerView = findViewById(R.id.recycler_view);

        sectionList.add("Morning");
        sectionList.add("Afternoon");
        sectionList.add("Evening");
        sectionList.add("Night");

        ArrayList<String> arrayList = new ArrayList<>();

        arrayList.add("05:00 AM");
        arrayList.add("06:00 AM");
        arrayList.add("07:00 AM");
        arrayList.add("08:00 AM");
        arrayList.add("09:00 AM");
        arrayList.add("10:00 AM");
        arrayList.add("11:00 AM");
        itemList.put(sectionList.get(0),arrayList);

        arrayList = new ArrayList<>();
        arrayList.add("12:00 PM");
        arrayList.add("01:00 PM");
        arrayList.add("02:00 PM");
        arrayList.add("03:00 PM");
        arrayList.add("04:00 PM");
        itemList.put(sectionList.get(1),arrayList);

        arrayList = new ArrayList<>();
        arrayList.add("05:00 PM");
        arrayList.add("06:00 PM");
        arrayList.add("07:00 PM");
        arrayList.add("08:00 PM");
        itemList.put(sectionList.get(2),arrayList);

        arrayList = new ArrayList<>();
        arrayList.add("09:00 PM");
        arrayList.add("10:00 PM");
        arrayList.add("11:00 PM");
        arrayList.add("12.00 PM");
        arrayList.add("01:00 PM");
        arrayList.add("02:00 PM");
        arrayList.add("03:00 PM");
        arrayList.add("04:00 PM");
        itemList.put(sectionList.get(3),arrayList);

        adapter = new MainAdapter2(this,sectionList,itemList);

        GridLayoutManager manager = new GridLayoutManager(this,3);

        recyclerView.setLayoutManager(manager);

        adapter.setLayoutManager(manager);
        adapter.shouldShowHeadersForEmptySections(false);
        recyclerView.setAdapter(adapter);
    }
}