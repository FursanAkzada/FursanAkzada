package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class PermissionLate implements Parcelable{
    private String id;
    private String name;
    private String nip;
    private String division;
    private String reason;
    private String img;
    private String date;
    private String device;
    private String latitude;
    private String longitude;
    private String location;
    private String employee_status;
    private String department;

    public PermissionLate() {
    }

    public PermissionLate(String id, String name, String nip, String division, String reason, String img, String date, String device, String latitude, String longitude, String location, String employee_status, String department) {
        this.id = id;
        this.name = name;
        this.nip = nip;
        this.division = division;
        this.reason = reason;
        this.img = img;
        this.date = date;
        this.device = device;
        this.latitude = latitude;
        this.longitude = longitude;
        this.location = location;
        this.employee_status = employee_status;
        this.department = department;
    }

    protected PermissionLate(Parcel in) {
        id = in.readString();
        name = in.readString();
        nip = in.readString();
        division = in.readString();
        reason = in.readString();
        img = in.readString();
        date = in.readString();
        device = in.readString();
        latitude = in.readString();
        longitude = in.readString();
        location = in.readString();
        employee_status = in.readString();
        department = in.readString();
    }

    public static final Creator<PermissionLate> CREATOR = new Creator<PermissionLate>() {
        @Override
        public PermissionLate createFromParcel(Parcel in) {
            return new PermissionLate(in);
        }

        @Override
        public PermissionLate[] newArray(int size) {
            return new PermissionLate[size];
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

    public String getNip() {
        return nip;
    }

    public void setNip(String nip) {
        this.nip = nip;
    }

    public String getDivision() {
        return division;
    }

    public void setDivision(String division) {
        this.division = division;
    }

    public String getReason() {
        return reason;
    }

    public void setReason(String reason) {
        this.reason = reason;
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

    public String getEmployee_status() {
        return employee_status;
    }

    public void setEmployee_status(String employee_status) {
        this.employee_status = employee_status;
    }

    public String getDepartment() {
        return department;
    }

    public void setDepartment(String department) {
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
        parcel.writeString(nip);
        parcel.writeString(division);
        parcel.writeString(reason);
        parcel.writeString(img);
        parcel.writeString(date);
        parcel.writeString(device);
        parcel.writeString(latitude);
        parcel.writeString(longitude);
        parcel.writeString(location);
        parcel.writeString(employee_status);
        parcel.writeString(department);
    }
}
