package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class MockTestModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<MockTestData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<MockTestData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class MockTestData(
        var id: Int? = null,
        var title: String? = null,
        var test_category_id: Int? = null,
        var course_id: Int? = null,
        var questions: Int? = 0,
        var test_time: Int? = 0,
        var type: Int? = 0,
        var created: String? = null,
    )
}