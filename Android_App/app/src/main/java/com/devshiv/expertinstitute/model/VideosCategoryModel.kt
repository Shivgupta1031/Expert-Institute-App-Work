package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class VideosCategoryModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<VideoCategoryData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<VideoCategoryData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class VideoCategoryData(
        var id: Int? = null,
        var category: String? = null,
        var created: String? = null,
    )
}