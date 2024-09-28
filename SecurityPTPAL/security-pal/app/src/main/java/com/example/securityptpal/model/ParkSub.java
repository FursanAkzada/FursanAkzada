package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class ParkSub implements Parcelable {
    private String id;
    private String masuk;
    private String keluar;

    public ParkSub() {
    }

    public ParkSub(String id, String masuk, String keluar) {
        this.id = id;
        this.masuk = masuk;
        this.keluar = keluar;
    }

    protected ParkSub(Parcel in) {
        id = in.readString();
        masuk = in.readString();
        keluar = in.readString();
    }

    public static final Creator<com.example.securityptpal.model.ParkSub> CREATOR = new Creator<com.example.securityptpal.model.ParkSub>() {
        @Override
        public com.example.securityptpal.model.ParkSub createFromParcel(Parcel in) {
            return new com.example.securityptpal.model.ParkSub(in);
        }

        @Override
        public com.example.securityptpal.model.ParkSub[] newArray(int size) {
            return new com.example.securityptpal.model.ParkSub[size];
        }
    };

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getMasuk() {
        return masuk;
    }

    public void setMasuk(String masuk) {
        this.masuk = masuk;
    }

    public String getKeluar() {
        return keluar;
    }

    public void setKeluar(String keluar) {
        this.keluar = keluar;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(masuk);
        parcel.writeString(keluar);
    }
}
