package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class MemberGuest implements Parcelable {
    private String id;
    private String name;

    protected MemberGuest(Parcel in) {
        id = in.readString();
        name = in.readString();
    }

    public MemberGuest(String id, String name) {
        this.id = id;
        this.name = name;
    }

    public static final Creator<MemberGuest> CREATOR = new Creator<MemberGuest>() {
        @Override
        public MemberGuest createFromParcel(Parcel in) {
            return new MemberGuest(in);
        }

        @Override
        public MemberGuest[] newArray(int size) {
            return new MemberGuest[size];
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

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(name);
    }
}
