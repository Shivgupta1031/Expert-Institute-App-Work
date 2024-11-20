package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class SyllabusModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<SyllabusData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<SyllabusData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class SyllabusData(
        var id: Int? = null,
        var title: String? = null,
        var file: String? = null,
        var created: String? = null,
    )
}