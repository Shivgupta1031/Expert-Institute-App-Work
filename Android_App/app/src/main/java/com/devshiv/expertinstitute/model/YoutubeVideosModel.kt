package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class YoutubeVideosModel(
    var message: String? = "",
    var data: String? = null
):java.io.Serializable {

    fun getDataDecrypted(): ArrayList<YoutubeVideosData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<YoutubeVideosData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class YoutubeVideosData(
        var id: Int? = null,
        var title: String? = null,
        var image: String? = null,
        var description: String? = null,
        var video_link: String? = null,
        var video_type: Int? = null,
        var course_id: Int? = null,
        var category_id: Int? = null,
        var created: String? = null,
    ):java.io.Serializable
}