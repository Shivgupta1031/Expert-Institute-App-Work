package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class UserProfileModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): UserProfileData? {
        val gson = Gson()
        val type: Type = object : TypeToken<UserProfileData?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class UserProfileData(
        var username: String = "",
        var email: String = "",
        var phone_number: String = "",
        var password: String = "",
        var password_decrypted: String = "",
        var state: String = "",
        var profile_pic: String = "",
    )
}