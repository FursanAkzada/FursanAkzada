package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class Guest implements Parcelable {
    private String id;
    private String name;
    private String company;
    private String phone;
    private String division;
    private String department;
    private String pic;
    private String necessity;
    private String date;
    private String timeIn;
    private String timeOut;
    private String division_approval;
    private String center_approval;

    public Guest() {
    }

    public Guest(String id, String name, String company, String phone, String division, String department, String pic, String necessity, String date, String timeIn, String timeOut, String division_approval, String center_approval) {
        this.id = id;
        this.name = name;
        this.company = company;
        this.phone = phone;
        this.division = division;
        this.department = department;
        this.pic = pic;
        this.necessity = necessity;
        this.date = date;
        this.timeIn = timeIn;
        this.timeOut = timeOut;
        this.division_approval = division_approval;
        this.center_approval = center_approval;
    }

    protected Guest(Parcel in) {
        id = in.readString();
        name = in.readString();
        company = in.readString();
        phone = in.readString();
        division = in.readString();
        department = in.readString();
        pic = in.readString();
        necessity = in.readString();
        date = in.readString();
        timeIn = in.readString();
        timeOut = in.readString();
        division_approval = in.readString();
        center_approval = in.readString();
    }

    public static final Creator<Guest> CREATOR = new Creator<Guest>() {
        @Override
        public Guest createFromParcel(Parcel in) {
            return new Guest(in);
        }

        @Override
        public Guest[] newArray(int size) {
            return new Guest[size];
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

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public String getDivision() {
        return division;
    }

    public String getDepartment() {
        return department;
    }

    public void setDepartment(String department) {
        this.department = department;
    }

    public void setDivision(String division) {
        this.division = division;
    }

    public String getPic() {
        return pic;
    }

    public void setPic(String pic) {
        this.pic = pic;
    }

    public String getNecessity() {
        return necessity;
    }

    public void setNecessity(String necessity) {
        this.necessity = necessity;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getTimeIn() {
        return timeIn;
    }

    public void setTimeIn(String timeIn) {
        this.timeIn = timeIn;
    }

    public String getTimeOut() {
        return timeOut;
    }

    public void setTimeOut(String timeOut) {
        this.timeOut = timeOut;
    }

    public String getDivision_approval() {
        return division_approval;
    }

    public void setDivision_approval(String division_approval) {
        this.division_approval = division_approval;
    }

    public String getCenter_approval() {
        return center_approval;
    }

    public void setCenter_approval(String center_approval) {
        this.center_approval = center_approval;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel parcel, int i) {
        parcel.writeString(id);
        parcel.writeString(name);
        parcel.writeString(company);
        parcel.writeString(phone);
        parcel.writeString(division);
        parcel.writeString(department);
        parcel.writeString(pic);
        parcel.writeString(necessity);
        parcel.writeString(date);
        parcel.writeString(timeIn);
        parcel.writeString(timeOut);
        parcel.writeString(division_approval);
        parcel.writeString(center_approval);
    }
}
