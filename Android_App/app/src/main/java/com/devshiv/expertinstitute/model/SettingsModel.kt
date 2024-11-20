package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class SettingsModel(
    var is_blocked: Boolean = false,
    var app_settings: String? = null,
) {

    fun getAppSettingsDecrypted(): SettingsData? {
        val gson = Gson()
        val type: Type = object : TypeToken<SettingsData?>() {}.type
        return gson.fromJson(Utils.decrypt(app_settings!!), type)
    }

    data class SettingsData(
        var contact_email: String = "",
        var privacy_policy: String = "",
        var more_apps_url: String = "",
        var share_message: String = "",
        var youtube_url: String = "",
        var whatsapp_number: String = "",
        var telegram_link: String = "",
        var instagram_link: String = "",
        var facebook_link: String = "",
        var onesignal_app_id: String = "",
        var app_download_link: String = "",
        var payment_method: Int = 0,
        var phonepe_merchant_id: String = "",
        var phonepe_salt_key: String = "",
        var phonepe_salt_index: String = "",
        var phonepe_host_url: String = "",
        var razorpay_api_key_id: String = "",
        var upi_manual_pay: String = "",
    )
}