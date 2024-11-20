package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.lang.reflect.Type

data class CouponModel(
    var message: String? = "",
    var data: String? = null
) {

    fun getDataDecrypted(): ArrayList<CouponData>? {
        val gson = Gson()
        val type: Type = object : TypeToken<ArrayList<CouponData>?>() {}.type
        return gson.fromJson(Utils.decrypt(data!!), type)
    }

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }

    data class CouponData(
        var id: Int? = null,
        var code: String? = null,
        var discount_amount: Int? = 0,
        var created: String? = null,
    )
}