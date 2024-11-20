package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class TestQuestionsModel(
    var message: String? = "",
    var data: String? = null
) : java.io.Serializable {

    fun getDataDecrypted(): ArrayList<TestQuestionsData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<TestQuestionsData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class TestQuestionsData(
        var id: Int? = null,
        var mock_test_id: String? = null,
        var question: String? = null,
        var opt_1: String? = null,
        var opt_2: String? = null,
        var opt_3: String? = null,
        var opt_4: String? = null,
        var correct_option_no: Int? = null,
        var user_answer: Int = 0,
        var created: String? = null,
    ) : java.io.Serializable
}