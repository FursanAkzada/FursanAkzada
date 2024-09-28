package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class Barang implements Parcelable {
    private String id;
    private String name;
    private String phone;
    private String pic;
    private String division;
    private String department;
    private String goods_name;
    private String type;
    private String img;
    private String date;
    private String device;
    private String latitude;
    private String longitude;
    private String location;
    private String status;

    public Barang() {
    }

    public Barang(String id, String name, String phone, String pic, String division, String department, String goods_name, String type, String img, String date, String device, String latitude, String longitude, String location, String status) {
        this.id = id;
        this.name = name;
        this.phone = phone;
        this.pic = pic;
        this.division = division;
        this.department = department;
        this.goods_name = goods_name;
        this.type = type;
        this.img = img;
        this.date = date;
        this.device = device;
        this.latitude = latitude;
        this.longitude = longitude;
        this.location = location;
        this.status = status;
    }

    protected Barang(Parcel in) {
        id = in.readString();
        name = in.readString();
        phone = in.readString();
        pic = in.readString();
        division = in.readString();
        department = in.readString();
        goods_name = in.readString();
        type = in.readString();
        img = in.readString();
        date = in.readString();
        device = in.readString();
        latitude = in.readString();
        longitude = in.readString();
        location = in.readString();
        status = in.readString();
    }

    public static final Creator<Barang> CREATOR = new Creator<Barang>() {
        @Override
        public Barang createFromParcel(Parcel in) {
            return new Barang(in);
        }

        @Override
        public Barang[] newArray(int size) {
            return new Barang[size];
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

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getPic() {
        return pic;
    }

    public void setPic(String pic) {
        this.pic = pic;
    }

    public String getDivision() {
        return division;
    }

    public void setDivision(String division) {
        this.division = division;
    }

    public String getDepartment() {
        return department;
    }

    public void setDepartment(String department) {
        this.department = department;
    }

    public String getGoods_name() {
        return goods_name;
    }

    public void setGoods_name(String goods_name) {
        this.goods_name = goods_name;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getImg() {
        return img;
    }

    public void setImg(String img) {
        this.img = img;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getDevice() {
        return device;
    }

    public void setDevice(String device) {
        this.device = device;
    }

    public String getLatitude() {
        return latitude;
    }

    public void setLatitude(String latitude) {
        this.latitude = latitude;
    }

    public String getLongitude() {
        return longitude;
    }

    public void setLongitude(String longitude) {
        this.longitude = longitude;
    }

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(name);
        parcel.writeString(phone);
        parcel.writeString(pic);
        parcel.writeString(division);
        parcel.writeString(department);
        parcel.writeString(goods_name);
        parcel.writeString(type);
        parcel.writeString(img);
        parcel.writeString(date);
        parcel.writeString(device);
        parcel.writeString(latitude);
        parcel.writeString(longitude);
        parcel.writeString(location);
        parcel.writeString(status);
    }
}
