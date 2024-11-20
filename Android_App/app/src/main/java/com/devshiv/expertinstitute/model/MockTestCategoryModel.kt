package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class MockTestCategoryModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<MockTestCategoryData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<MockTestCategoryData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class MockTestCategoryData(
        var id: Int? = null,
        var title: String? = null,
        var price: Int? = null,
        var is_purchased: Boolean = false,
        var created: String? = null,
    )
}