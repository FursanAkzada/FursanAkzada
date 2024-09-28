package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class Division implements Parcelable {
    private String id;
    private String name;
    List<String> department;

    public Division() {
    }

    public Division(String id, String name, List<String> department) {
        this.id = id;
        this.name = name;
        this.department = department;
    }

    protected Division(Parcel in) {
        id = in.readString();
        name = in.readString();
        department = in.createStringArrayList();
    }

    public static final Creator<Division> CREATOR = new Creator<Division>() {
        @Override
        public Division createFromParcel(Parcel in) {
            return new Division(in);
        }

        @Override
        public Division[] newArray(int size) {
            return new Division[size];
        }
    };

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public List<String> getDepartment() {
        return department;
    }

    public void setDepartment(List<String> department) {
        this.department = department;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(name);
        parcel.writeStringList(department);
    }
}
