package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class EmployeeSubcon implements Parcelable {
    private String id;
    private String name;
    private String ttl;
    private String phone;
    private String position;
    private String location;
    private String address;
    private String start;
    private String finish;
    private String periode;
    private String img;

    public EmployeeSubcon() {
    }

    public EmployeeSubcon(String id, String periode, String name, String ttl, String phone, String position, String location, String start, String finish, String address, String img) {
        this.id = id;
        this.periode = periode;
        this.name = name;
        this.ttl = ttl;
        this.phone = phone;
        this.position = position;
        this.location = location;
        this.start = start;
        this.finish = finish;
        this.address = address;
        this.img = img;
    }

    protected EmployeeSubcon(Parcel in) {
        id = in.readString();
        periode = in.readString();
        name = in.readString();
        ttl = in.readString();
        phone = in.readString();
        position = in.readString();
        location = in.readString();
        start = in.readString();
        finish = in.readString();
        address = in.readString();
        img = in.readString();
    }

    public static final Creator<EmployeeSubcon> CREATOR = new Creator<EmployeeSubcon>() {
        @Override
        public EmployeeSubcon createFromParcel(Parcel in) {
            return new EmployeeSubcon(in);
        }

        @Override
        public EmployeeSubcon[] newArray(int size) {
            return new EmployeeSubcon[size];
        }
    };

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getPeriode() {
        return periode;
    }

    public void setPeriode(String position) {
        this.name = position;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getTtl() {
        return ttl;
    }

    public void setTtl(String age) {
        this.ttl = age;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getPosition() {
        return position;
    }

    public void setPosition(String position) {
        this.position = position;
    }

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public String getStart() {
        return start;
    }

    public void setStart(String start) {
        this.start = start;
    }

    public String getFinish() {
        return finish;
    }

    public void setFinish(String finish) {
        this.finish = finish;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getImg() {
        return img;
    }

    public void setImg(String img) {
        this.img = img;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(periode);
        parcel.writeString(name);
        parcel.writeString(ttl);
        parcel.writeString(phone);
        parcel.writeString(position);
        parcel.writeString(location);
        parcel.writeString(start);
        parcel.writeString(finish);
        parcel.writeString(address);
        parcel.writeString(img);
    }
}
