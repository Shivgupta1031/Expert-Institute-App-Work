package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class EbooksModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<EbookData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<EbookData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class EbookData(
        var id: Int? = null,
        var title: String? = null,
        var image: String? = null,
        var file: String? = null,
        var is_purchased: Boolean = false,
        var price: Int? = null,
        var created: String? = null,
    )
}