package com.example.securityptpal.model;

import android.os.Parcel;
import android.os.Parcelable;

public class PermissionEmployee implements Parcelable {
    private String id;
    private String base;
    private String name;
    private String nip;
    private String division;
    private String date;
    private String necessity;
    private String place;
    private String timeout;
    private String timeback;
    private String division_approval;
    private String center_approval;
    private String employee_status;
    private String department;

    public PermissionEmployee() {
    }

    public PermissionEmployee(String id, String base, String name, String nip, String division, String date, String necessity, String place, String timeout, String timeback, String division_approval, String center_approval, String employee_status, String department)  {
        this.id = id;
        this.base = base;
        this.name = name;
        this.nip = nip;
        this.division = division;
        this.date = date;
        this.necessity = necessity;
        this.place = place;
        this.timeout = timeout;
        this.timeback = timeback;
        this.division_approval = division_approval;
        this.center_approval = center_approval;
        this.employee_status = employee_status;
        this.department = department;
    }

    protected PermissionEmployee(Parcel in) {
        id = in.readString();
        base = in.readString();
        name = in.readString();
        nip = in.readString();
        division = in.readString();
        date = in.readString();
        necessity = in.readString();
        place = in.readString();
        timeout = in.readString();
        timeback = in.readString();
        division_approval = in.readString();
        center_approval = in.readString();
        employee_status = in.readString();
        department = in.readString();
    }

    public static final Creator<PermissionEmployee> CREATOR = new Creator<PermissionEmployee>() {
        @Override
        public PermissionEmployee createFromParcel(Parcel in) {
            return new PermissionEmployee(in);
        }

        @Override
        public PermissionEmployee[] newArray(int size) {
            return new PermissionEmployee[size];
        }
    };

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getBase() {
        return base;
    }

    public void setBase(String base) {
        this.base = base;
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

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getNecessity() {
        return necessity;
    }

    public void setNecessity(String necessity) {
        this.necessity = necessity;
    }

    public String getPlace() {
        return place;
    }

    public void setPlace(String place) {
        this.place = place;
    }

    public String getTimeout() {
        return timeout;
    }

    public void setTimeout(String timeout) {
        this.timeout = timeout;
    }

    public String getTimeback() {
        return timeback;
    }

    public void setTimeback(String timeback) {
        this.timeback = timeback;
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
        parcel.writeString(base);
        parcel.writeString(name);
        parcel.writeString(nip);
        parcel.writeString(division);
        parcel.writeString(date);
        parcel.writeString(necessity);
        parcel.writeString(place);
        parcel.writeString(timeout);
        parcel.writeString(timeback);
        parcel.writeString(division_approval);
        parcel.writeString(center_approval);
        parcel.writeString(employee_status);
        parcel.writeString(department);
    }
}
