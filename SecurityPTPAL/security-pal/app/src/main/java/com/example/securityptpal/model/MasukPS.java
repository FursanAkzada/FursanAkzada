package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class MasukPS implements Parcelable {
    private String id;
    private String name;
    private String nopol;
    private String date;
    private String queue;

    public MasukPS(Parcel in) {
        id = in.readString();
        name = in.readString();
        nopol = in.readString();
        date = in.readString();
        queue = in.readString();
    }

    public MasukPS(String id, String name, String nopol, String date, String queue) {
        this.id = id;
        this.name = name;
        this.nopol = nopol;
        this.date = date;
        this.queue = queue;
    }

    public static final Creator<com.example.securityptpal.model.MasukPS> CREATOR = new Creator<MasukPS>() {
        @Override
        public MasukPS createFromParcel(Parcel in) {
            return new MasukPS(in);
        }

        @Override
        public MasukPS[] newArray(int size) {
            return new MasukPS[size];
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

    public String getNopol() {
        return nopol;
    }

    public void setNopol(String nopol) {
        this.nopol = nopol;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getQueue() {
        return queue;
    }

    public void setQueue(String queue) {
        this.queue = queue;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(name);
        parcel.writeString(nopol);
        parcel.writeString(date);
        parcel.writeString(queue);
    }
}
