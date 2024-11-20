package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class PCourseModel(
    var message: String? = "",
    var data: String? = null
) : java.io.Serializable {

    fun getDataDecrypted(): PaidCourseData? {
        val gson = Gson()
        val type: Type = object : TypeToken<PaidCourseData>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

}