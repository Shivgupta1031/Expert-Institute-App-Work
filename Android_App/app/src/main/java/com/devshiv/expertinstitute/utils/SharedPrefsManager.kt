package com.devshiv.expertinstitute.utils

import android.content.Context

object SharedPrefsManager {

    fun setUid(context: Context, username: String?) {
        context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE).edit()
            .putString(Variables.UID_TAG, username).apply()
    }

    fun getUid(context: Context?): String? {
        if (context == null) {
            return "unknown"
        }
        return if (context.getSharedPreferences(
                Variables.SHARED_PREFS,
                Context.MODE_PRIVATE
            ) == null
        ) {
            "unknown"
        } else context.getSharedPreferences(
            Variables.SHARED_PREFS,
            Context.MODE_PRIVATE
        ).getString(Variables.UID_TAG, "Unknown")
    }

    fun setPassword(context: Context, username: String?) {
        context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE).edit()
            .putString(Variables.PASSWORD_TAG, username).apply()
    }

    fun getPassword(context: Context?): String? {
        if (context == null) {
            return "unknown"
        }
        return if (context.getSharedPreferences(
                Variables.SHARED_PREFS,
                Context.MODE_PRIVATE
            ) == null
        ) {
            ""
        } else context.getSharedPreferences(
            Variables.SHARED_PREFS,
            Context.MODE_PRIVATE
        ).getString(Variables.PASSWORD_TAG, "")
    }

    fun setPhoneNumber(context: Context, username: String?) {
        context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE).edit()
            .putString(Variables.PHONE_NUMBER_TAG, username).apply()
    }

    fun getPhoneNumber(context: Context?): String? {
        if (context == null) {
            return "unknown"
        }
        return if (context.getSharedPreferences(
                Variables.SHARED_PREFS,
                Context.MODE_PRIVATE
            ) == null
        ) {
            ""
        } else context.getSharedPreferences(
            Variables.SHARED_PREFS,
            Context.MODE_PRIVATE
        ).getString(Variables.PHONE_NUMBER_TAG, "")
    }

    fun setUsername(context: Context, username: String?) {
        context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE).edit()
            .putString(Variables.USERNAME_TAG, username).apply()
    }

    fun getUsername(context: Context?): String? {
        if (context == null) {
            return "unknown"
        }
        return if (context.getSharedPreferences(
                Variables.SHARED_PREFS,
                Context.MODE_PRIVATE
            ) == null
        ) {
            ""
        } else context.getSharedPreferences(
            Variables.SHARED_PREFS,
            Context.MODE_PRIVATE
        ).getString(Variables.USERNAME_TAG, "")
    }

    fun getToken(context: Context): String? {
        return context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE)
            .getString(Variables.TOKEN, "")
    }

    fun setToken(context: Context, token: String?) {
        context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE).edit()
            .putString(Variables.TOKEN, token).apply()
    }

    fun getLoginStatus(context: Context): Boolean {
        return context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE)
            .getBoolean(Variables.LOGGED_IN_PREFS, false)
    }

    fun setLoginStatus(context: Context, status: Boolean) {
        context.getSharedPreferences(Variables.SHARED_PREFS, Context.MODE_PRIVATE).edit()
            .putBoolean(Variables.LOGGED_IN_PREFS, status).apply()
    }

}