package com.devshiv.expertinstitute.model

import com.devshiv.expertinstitute.utils.Utils

class MessageModel(
    var message: String? = "",
) {

    fun getMessageDecrypted(): String? {
        return if (message != null) {
            Utils.decrypt(message!!)
        } else {
            ""
        }
    }
}