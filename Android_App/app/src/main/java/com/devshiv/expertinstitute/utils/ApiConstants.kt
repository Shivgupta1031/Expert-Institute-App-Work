package com.devshiv.expertinstitute.utils

object ApiConstants {

    init {
        System.loadLibrary("keys")
    }

    val authToken: String
        external get
    val token: String
        get() = authToken
    val apiKey: String
        external get
    val apiUrl: String
        external get
    val api: String
        get() = apiUrl

    const val AUTH = "Bearer "

    val getSettings_api: String = api + "getSettings"
    val registerUser_api: String = api + "registerUser"
    val loginUser_api: String = api + "loginUser"
    val forgotPass_api: String = api + "forgotPass"

    val getBanners_api: String = api + "getBannersData"
    val getPaidCoursesData_api: String = api + "getPaidCoursesData"
    val getVideosCategoryData_api: String = api + "getVideosCategoryData"
    val getVideosData_api: String = api + "getVideosData"
    val getEbooksData_api: String = api + "getEbooksData"
    val getSyllabusData_api: String = api + "getSyllabusData"
    val getNotificationsData_api: String = api + "getNotificationsData"
    val getUserProfileData_api: String = api + "getUserProfileData"
    val saveUserProfileData_api: String = api + "saveUserProfileData"
    val getPaidCourse_api: String = api + "getPaidCourse"
    val getMockTestCategoryData_api: String = api + "getMockTestCategoryData"
    val getMockTestData_api: String = api + "getMockTestData"
    val getMockTestQuestionsData_api: String = api + "getMockTestQuestionsData"
    val getUserOrdersData_api: String = api + "getUserOrdersData"
    val getPDFNotesData_api: String = api + "getPDFNotesData"
    val getUpcomingLiveVideosData_api: String = api + "getUpcomingLiveVideosData"

    val getCouponCodesData_api: String = api + "getCoupanCodesData"
    val createPhonePePaymentRequest_api: String = api + "createPhonePePaymentRequest"

    val purchaseItem_api: String = api + "purchaseItem"
    val createWithdrawal_api: String = api + "createWithdrawal"
    val createManualPaymentRequest_api: String = api + "createManualPaymentRequest"

}