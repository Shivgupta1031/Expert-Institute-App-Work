package com.devshiv.expertinstitute.utils

import android.util.Log
import java.io.File

class RootChecker {

    companion object {
        private val TAG = RootChecker::class.java.simpleName
        private val pathList: Array<String> = arrayOf(
            "/sbin/",
            "/system/bin/",
            "/system/xbin/",
            "/data/local/xbin/",
            "/data/local/bin/",
            "/system/sd/xbin/",
            "/system/bin/failsafe/",
            "/data/local/"
        )
        private const val KEY_SU = "su"

        val isDeviceRooted: Boolean
            get() = doesFileExists(KEY_SU)

        /**
         * Checks the all path until it finds it and return immediately.
         *
         * @param value must be only the binary name
         * @return if the value is found in any provided path
         */
        private fun doesFileExists(value: String): Boolean {
            var result = false
            for (path in pathList) {
                val file = File("$path/$value")
                result = file.exists()
                if (result) {
                    Log.d(TAG, "$path contains su binary")
                    break
                }
            }
            return result
        }
    }
}