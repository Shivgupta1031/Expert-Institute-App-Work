package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class PDFNotesModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<PDFNotesData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<PDFNotesData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class PDFNotesData(
        var id: Int? = null,
        var title: String? = null,
        var file: String? = null,
        var course_id: String? = null,
        var created: String? = null,
    )
}