package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class OrderRequestModel(
    var message: String? = "",
    var data: String? = "",
){

    fun getDataDecrypted(): OrderRequestData? {
        val gson = Gson()
        val type: Type = object : TypeToken<OrderRequestData?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class OrderRequestData(
        var order_id: String = "",
        var name: String = "",
        var phone: String = "",
        var email: String = "",
        var amount: String = "",
    )
}