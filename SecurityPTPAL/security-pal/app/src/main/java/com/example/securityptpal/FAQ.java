package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;

import com.example.securityptpal.main.AkunUtama;

import java.util.ArrayList;
import java.util.List;

public class FAQ extends AppCompatActivity {

    private RecyclerView recyclerView;
    private List<DataModel> mList;
    private ItemAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_faq);

        recyclerView = findViewById(R.id.main_recyclervie);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        mList = new ArrayList<>();

        //list1
        List<String> nestedList1 = new ArrayList<>();
        nestedList1.add("After you send permission and confirmation, the application will send your approved permit application containing the QR Code. At this stage, you can be sure that your permit has been approved");

        List<String> nestedList2 = new ArrayList<>();
        nestedList2.add("Employee -> Monitoring -> Enter your NIP in searchview");
        nestedList2.add("Cometoolate -> Monitoring -> Enter your NIP in searchview");
        nestedList2.add("Check Up -> Monitoring -> Enter your NIP in searchview");
        nestedList2.add("Subcontractor -> Monitoring -> Enter your Company Name in searcview");
        nestedList2.add("Guest -> Monitoring -> Enter your Name in searchview");
        nestedList2.add("Visitor -> Monitoring -> Enter your Name in searchview");
        nestedList2.add("Goods -> Monitoring -> Enter the Goods Name in searchview");

        List<String> nestedList3 = new ArrayList<>();
        nestedList3.add("Screenshot QR code is not allowed to be used as a valid document at the time of scanning");

        List<String> nestedList4 = new ArrayList<>();
        nestedList4.add("Call your Advisor");

        List<String> nestedList5 = new ArrayList<>();
        nestedList5.add("Remember Login");
        nestedList5.add("Capture Image and Set Image");
        nestedList5.add("Zoom In Image");
        nestedList5.add("Global Positioning System (GPS)");
        nestedList5.add("Update and Delete Data");
        nestedList5.add("Create QRCode");
        nestedList5.add("Create ID Card");
        nestedList5.add("Export Data to .xls file from Main and Division Account");
        nestedList5.add("Filter Data from Oldest to Newest and Vice Versa");
        nestedList5.add("Search Data");
        nestedList5.add("FAQ");

        List<String> nestedList6 = new ArrayList<>();
        nestedList6.add("You can go to montoring and enter your company name in searchview, then long press on data, the option to add members will appear");

        mList.add(new DataModel(nestedList1, "How to know your permit approved ?"));
        mList.add(new DataModel(nestedList2,"How to check permission status ?"));
        mList.add(new DataModel(nestedList3,"QR Code screenshot be used for scanning ?"));
        mList.add(new DataModel(nestedList4,"How to make permission changes ?"));
        mList.add(new DataModel(nestedList5,"Application Features"));
        mList.add(new DataModel(nestedList6,"How to add Subcon members ?"));

        adapter = new ItemAdapter(mList);
        recyclerView.setAdapter(adapter);
    }

}