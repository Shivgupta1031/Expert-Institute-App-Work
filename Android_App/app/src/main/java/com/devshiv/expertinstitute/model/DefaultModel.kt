package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

class DefaultModel(
    var is_blocked: Boolean = false,
    var message: String? = "",
    var data: String = "",
    var token: String = "",
    var app_settings: String? = null
) {
    fun getAppSettingsDecrypted(): SettingsModel.SettingsData? {
        val gson = Gson()
        val type: Type = object : TypeToken<SettingsModel.SettingsData?>() {}.type
        return gson.fromJson(Utils.decrypt(app_settings!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    fun getDataDecrypted(): String? {
        return Utils.decrypt(data)
    }

    fun getTokenDecrypted(): String? {
        return Utils.decrypt(token)
    }
}