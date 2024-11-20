package com.devshiv.expertinstitute.utils

import android.app.Activity
import android.app.Dialog
import android.content.Context
import android.content.DialogInterface
import android.content.pm.PackageManager
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.net.ConnectivityManager
import android.net.Uri
import android.os.Build
import android.provider.Settings
import android.util.Base64
import android.util.Log
import android.view.*
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.AlertDialogLayoutBinding
import com.devshiv.expertinstitute.databinding.CustomDialogLayoutBinding
import com.devshiv.expertinstitute.databinding.LoadingDialogBinding
import com.devshiv.expertinstitute.utils.Variables.TAG
import com.instacart.library.truetime.TrueTime
import java.io.*
import java.net.NetworkInterface
import java.net.SocketException
import java.security.MessageDigest
import java.security.NoSuchAlgorithmException
import java.text.ParseException
import java.text.SimpleDateFormat
import java.util.*


class Utils {

    companion object {

        var loadingDialog: Dialog? = null

        fun showMessageDialog(
            activity: Activity,
            title: String?,
            message: String?,
            isCancelable: Boolean,
            dialogCallback: DialogInterface.OnDismissListener?
        ) {
            if (!activity.isFinishing) {
                val customDialog = Dialog(activity)
                val binding: CustomDialogLayoutBinding =
                    CustomDialogLayoutBinding.inflate(LayoutInflater.from(customDialog.context))
                customDialog.setContentView(binding.root)
                try {
//                    customDialog.window?.setDimAmount(1F)
                    customDialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))
                    customDialog.window?.attributes?.windowAnimations =
                        R.style.MyDialogAnimation_Dialog //style id
                } catch (e: Exception) {
                    Log.d(TAG, "showLoading: " + e.message)
                }
                customDialog.setCanceledOnTouchOutside(true)
                customDialog.setCancelable(isCancelable)
                binding.titleTxt.text = title
                binding.descriptionTxt.text = message
                binding.okBtn.setOnClickListener {
                    customDialog.dismiss()
                }
                if (dialogCallback != null) {
                    customDialog.setOnDismissListener(dialogCallback)
                }
                customDialog.show()
            }
        }

        fun showAlertDialog(
            activity: Activity,
            title: String?,
            message: String?,
            isCancelable: Boolean,
            dialogCallback: MyOnClickListener?
        ) {
            if (!activity.isFinishing) {
                val customDialog = Dialog(activity)
                val binding: AlertDialogLayoutBinding =
                    AlertDialogLayoutBinding.inflate(LayoutInflater.from(customDialog.context))
                customDialog.setContentView(binding.root)
                try {
//                    customDialog.window?.setDimAmount(1F)
                    customDialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))
                    customDialog.window?.attributes?.windowAnimations =
                        R.style.MyDialogAnimation_Dialog //style id
                } catch (e: Exception) {
                    Log.d(TAG, "showLoading: " + e.message)
                }
                customDialog.setCanceledOnTouchOutside(true)
                customDialog.setCancelable(isCancelable)
                binding.titleTxt.text = title
                binding.descriptionTxt.text = message
                binding.cancelBtn.setOnClickListener {
                    customDialog.dismiss()
                }
                if (dialogCallback != null) {
                    binding.yesBtn.setOnClickListener {
                        customDialog.dismiss()
                        dialogCallback.onClick("")
                    }
                }
                customDialog.show()
            }
        }

        fun showLoading(
            activity: Activity,
            isCancelable: Boolean
        ) {
            if (!activity.isFinishing) {
                loadingDialog = Dialog(activity, android.R.style.Theme_Black_NoTitleBar_Fullscreen)
                val binding: LoadingDialogBinding =
                    LoadingDialogBinding.inflate(LayoutInflater.from(loadingDialog!!.context))
                loadingDialog!!.setContentView(binding.root)
                try {
//                    loadingDialog!!.window?.setDimAmount(0F)
                    loadingDialog!!.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))
                    loadingDialog!!.window?.attributes?.windowAnimations =
                        R.style.MyDialogAnimation_Dialog //style id
                } catch (e: Exception) {
                    Log.d(TAG, "showLoading: " + e.message)
                }
                loadingDialog!!.setCanceledOnTouchOutside(false)
                loadingDialog!!.setCancelable(isCancelable)
                loadingDialog!!.show()
            }
        }

        fun cancelLoading() {
            if (loadingDialog != null && loadingDialog!!.isShowing) {
                try {
                    loadingDialog!!.cancel()
                    loadingDialog = null
                } catch (e: java.lang.Exception) {
                    Log.d(TAG, "cancelLoading: " + e.message)
                }
            }
        }

        fun appInstalledOrNot(activity: Activity, url: String): Boolean {
            val packageManager: PackageManager = activity.packageManager
            val app_installed: Boolean = try {
                packageManager.getPackageInfo(url, PackageManager.GET_ACTIVITIES)
                true
            } catch (e: PackageManager.NameNotFoundException) {
                false
            }
            return app_installed
        }

        fun isInternetConnected(ctx: Context): Boolean {
            val cm = ctx.getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
            val nInfo = cm.activeNetworkInfo
            return nInfo != null && nInfo.isConnectedOrConnecting
        }

        fun isVpnConnected(): Boolean {
            var iface = ""
            try {
                for (networkInterface in Collections.list(NetworkInterface.getNetworkInterfaces())) {
                    if (networkInterface.isUp) iface = networkInterface.name
                    if (iface.contains("tun") || iface.contains("ppp") || iface.contains("pptp")) {
                        return true
                    }
                }
            } catch (e1: SocketException) {
                Log.d(TAG, "isVpnConnected: " + e1.message)
            }
            return false
        }

        fun getDeviceId(ctx: Context): String? {
            return Settings.Secure.getString(ctx.contentResolver, Settings.Secure.ANDROID_ID)
        }

        fun getEncryptedDeviceId(ctx: Context): String? {
            val android_id = Settings.Secure.getString(
                ctx.contentResolver,
                Settings.Secure.ANDROID_ID
            )
            return md5(android_id)!!.uppercase(Locale.getDefault())
        }

        fun md5(input: String): String? {
            return try {
                val digest = MessageDigest.getInstance("MD5")
                digest.update(input.toByteArray())
                val messageDigest = digest.digest()
                val hexString = StringBuilder()
                for (i in messageDigest.indices) hexString.append(
                    String.format(
                        "%02x",
                        messageDigest[i]
                    )
                )
                hexString.toString()
            } catch (e: NoSuchAlgorithmException) {
                e.printStackTrace()
                null
            }
        }

        fun encrypt(coded: String): String? {
            var coded = coded
            coded = encrypt_code(coded)
            val encodeValue = Base64.encode(coded.toByteArray(), Base64.DEFAULT)
            return String(encodeValue)
        }

        fun encrypt_code(coded: String): String {
            val encodeValue = Base64.encode(coded.toByteArray(), Base64.DEFAULT)
            return String(encodeValue)
        }

        fun decryption(coded: String?): String {
            if (coded == null) {
                return ""
            }
            var valueDecoded: ByteArray? = ByteArray(0)
            try {
                valueDecoded = Base64.decode(coded.toByteArray(charset("UTF-8")), Base64.DEFAULT)
            } catch (e: UnsupportedEncodingException) {
                Log.d(TAG, "decryption: " + e.message)
            }
            return String(valueDecoded!!)
        }

        fun decrypt(coded: String?): String? {
            if (coded == null) {
                return ""
            }
            var coded = coded
            coded = decryption(coded)
            var valueDecoded: ByteArray? = ByteArray(0)
            try {
                valueDecoded = Base64.decode(coded.toByteArray(charset("UTF-8")), Base64.DEFAULT)
            } catch (e: UnsupportedEncodingException) {
                Log.d(TAG, "decrypt: " + e.message)
            }
            return String(valueDecoded!!)
        }

        fun getDateAfter(sDate: String, time: Int): String? {
            return if (sDate.trim { it <= ' ' }.isEmpty()) {
                ""
            } else try {
                val sdf = SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
                val date = sdf.parse(sDate)
                val calendar = Calendar.getInstance()
                calendar.time = date
                calendar.add(Calendar.SECOND, time)
                sdf.format(calendar.time)
            } catch (e: ParseException) {
                Log.d(TAG, "getDateAfter: " + e.message)
                ""
            }
        }

        fun checkIsDateAfter(date: String): Boolean {
            if (date.trim { it <= ' ' }.isEmpty()) {
                return true
            }
            val format = SimpleDateFormat("yyyy-MM-dd HH:mm:ss")
            return try {
                val d = format.parse(date)
                if (TrueTime.isInitialized()) {
                    val c: Date = TrueTime.now()
                    return c.after(d)
                }
                //            Date c = Calendar.getInstance().getTime();
                true
            } catch (e: ParseException) {
                Log.d(TAG, "checkIsDateAfter: " + e.message)
                true
            }
        }

        fun getCurrentTimeMillis(): Long {
            val currentDate: Date = if (TrueTime.isInitialized()) {
                TrueTime.now()
            } else {
                Calendar.getInstance().time
            }
            val calendar = Calendar.getInstance()
            calendar.time = currentDate
            return calendar.timeInMillis
        }

        fun getRequestHeader(context: Context?): Map<String, String>? {
            val headers: MutableMap<String, String> = HashMap()
            headers["Accept"] = "application/json"
            headers["Authorization"] = ApiConstants.AUTH + SharedPrefsManager.getToken(context!!)
            headers["User-Agent"] = ApiConstants.apiKey
            return headers
        }

        fun getRequestFileHeader(context: Context?): Map<String, String>? {
            val headers: MutableMap<String, String> = HashMap()
            headers["Accept"] = "multipart/form-data"
            headers["Authorization"] = ApiConstants.AUTH + SharedPrefsManager.getToken(context!!)
            headers["User-Agent"] = ApiConstants.apiKey
            return headers
        }

        fun Window.transparentStatusBar() {
            setWindowFlag(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS, true)
            decorView.systemUiVisibility =
                View.SYSTEM_UI_FLAG_LAYOUT_STABLE or View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
            if (Build.VERSION.SDK_INT >= 21) {
                setWindowFlag(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS, false)
                statusBarColor = Color.TRANSPARENT
            }
        }

        fun Window.transparentStatusAndNavigation() {
            decorView.systemUiVisibility = (View.SYSTEM_UI_FLAG_LAYOUT_STABLE
                    or View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
                    or View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION)
            setWindowFlag(
                (WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS
                        or WindowManager.LayoutParams.FLAG_TRANSLUCENT_NAVIGATION), false
            )
            statusBarColor = Color.TRANSPARENT
            navigationBarColor = Color.TRANSPARENT
        }

        fun Window.setWindowFlag(bits: Int, on: Boolean) {
            val winParams = attributes
            if (on) {
                winParams.flags = winParams.flags or bits
            } else {
                winParams.flags = winParams.flags and bits.inv()
            }
            attributes = winParams
        }

        fun copyFileToCache(context: Context, documentUri: Uri, extension: String): File {
            val destFile = File(context.cacheDir.absolutePath + "/" + getFileName(extension))
            try {
                val parcelFileDescriptor =
                    context.contentResolver.openFileDescriptor(documentUri, "r")
                val fileDescriptor: FileDescriptor = parcelFileDescriptor!!.fileDescriptor
                if (!destFile.exists()) {
                    destFile.createNewFile()
                }
                try {
                    FileInputStream(fileDescriptor).getChannel().use { sourceChannel ->
                        FileOutputStream(destFile).getChannel().use { destination ->
                            destination.transferFrom(
                                sourceChannel,
                                0,
                                sourceChannel.size()
                            )
                        }
                    }
                } catch (e: java.lang.Exception) {
                    Log.d(TAG, "copyFileToCache: " + e.message)
                }
            } catch (e: java.lang.Exception) {
                Log.d(TAG, "copyFileToCache: " + e.message)
            }
            return destFile
        }

        fun getFileName(extension: String): String {
            return System.currentTimeMillis().toString() + extension
        }

        fun getFilePath(context: Context, extension: String): String {
            return context.cacheDir.absolutePath + "/" + System.currentTimeMillis() + extension
        }
    }
}