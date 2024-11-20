package com.devshiv.expertinstitute.utils

import com.devshiv.expertinstitute.model.SettingsModel

object Variables {


    const val TAG = "MyTag"

    var settingsModel: SettingsModel.SettingsData = SettingsModel.SettingsData()

    const val UID_TAG: String = "UID"

    const val USERNAME_TAG = "USERNAME"
    const val PHONE_NUMBER_TAG: String = "Phone Number"
    const val EMAIL_TAG: String = "Email"
    const val PASSWORD_TAG: String = "Password"

    const val SHARED_PREFS = "SHARED PREFS"
    const val LOGGED_IN_PREFS = "LOGGED IN"

    const val TOKEN: String = "TOKEN"

    // For User Orders Model
    const val PAID_COURSE_ORDER = 0
    const val EBOOK_ORDER = 1
    const val MOCK_TEST_CATEGORY_ORDER = 2
    const val PRODUCT_ORDER = 7
    const val MOCK_TEST_ORDER = 2

    const val RAZORPAY = 0
    const val PHONE_PE = 1
    const val MANUAL_PAYMENT = 2

    const val NOTICE_TITLE = "Notice Title"
    const val NOTICE_MESSAGE = "Notice Message"
    const val NOTICE_IMAGE = "Notice Image"
    const val NOTICE_URL = "Notice Url"
}