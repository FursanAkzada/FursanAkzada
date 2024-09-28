package com.example.securityptpal;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

public class Preferences {
    private static final String DATA_LOGIN = "status_login",
            DATA_ROLE = "role";

    private static SharedPreferences getSharedReferences(Context context) {
        return PreferenceManager.getDefaultSharedPreferences(context);
    }

    public static void setDataRole(Context context, String data) {
        SharedPreferences.Editor editor = getSharedReferences(context).edit();
        editor.putString(DATA_ROLE, data);
        editor.apply();
    }

    public static String getDataRole(Context context){
        return getSharedReferences(context).getString(DATA_ROLE, "");
    }

    public static void setDataLogin(Context context, boolean status) {
        SharedPreferences.Editor editor = getSharedReferences(context).edit();
        editor.putBoolean(DATA_LOGIN, status);
        editor.apply();
    }

    public static boolean getDataLogin(Context context) {
        return getSharedReferences(context).getBoolean(DATA_LOGIN, false);
    }

    public static void clearData(Context context) {
        SharedPreferences.Editor editor = getSharedReferences(context).edit();
        editor.remove(DATA_ROLE);
        editor.remove(DATA_LOGIN);
        editor.apply();
    }
}
