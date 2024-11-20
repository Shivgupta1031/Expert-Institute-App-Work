package com.devshiv.expertinstitute.model

data class PaidCourseData(
    var id: Int? = null,
    var title: String? = null,
    var image: String? = null,
    var description: String? = null,
    var is_purchased: Boolean = false,
    var price: Int? = null,
    var created: String? = null,
) : java.io.Serializable