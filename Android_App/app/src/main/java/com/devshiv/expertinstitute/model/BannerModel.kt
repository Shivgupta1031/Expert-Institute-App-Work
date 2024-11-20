package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type


data class BannerModel(
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<BannerData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<BannerData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    data class BannerData(
        var image: String? = null,
        var url: String? = null,
        var type: Int = 0,
    )
}