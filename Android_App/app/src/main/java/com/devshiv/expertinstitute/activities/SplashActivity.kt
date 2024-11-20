package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.os.Bundle
import android.os.Process
import android.util.Log
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.daimajia.androidanimations.library.Techniques
import com.daimajia.androidanimations.library.YoYo
import com.devshiv.expertinstitute.databinding.ActivitySplashBinding
import com.devshiv.expertinstitute.model.DefaultModel
import com.devshiv.expertinstitute.model.SettingsModel
import com.devshiv.expertinstitute.utils.*
import com.devshiv.expertinstitute.utils.Variables.TAG
import com.onesignal.OneSignal
import com.onesignal.debug.LogLevel
import org.json.JSONException
import org.json.JSONObject

class SplashActivity : AppCompatActivity() {

    lateinit var binding: ActivitySplashBinding
    private val APP_PACKAGE_DOT_COUNT = 3 // number of dots present in package name
    private val DUAL_APP_ID_999 = "apps.clone.cloud.multiple.space.bit64"
    private val DOT = '.'

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivitySplashBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.splashImg.post{
            YoYo.with(Techniques.ZoomInUp)
                .duration(700)
                .playOn(binding.splashImg)
        }

        if (!Utils.isInternetConnected(this@SplashActivity)) {
            Toast.makeText(this, "No Internet Connection", Toast.LENGTH_SHORT).show()
        } else {
            checkAppCloning()
            if (Utils.isVpnConnected()) {
                Toast.makeText(this, "VPN Detected", Toast.LENGTH_SHORT).show()
            }
            if (RootChecker.isDeviceRooted) {
                Toast.makeText(this, "Running App On Rooted Device", Toast.LENGTH_SHORT).show()
            }
            if (SharedPrefsManager.getLoginStatus(this@SplashActivity)) {
                loginUser()
            } else {
                getData()
            }
        }

    }

    private fun getData() {
        val jsonObject = JSONObject()
        try {
            jsonObject.put("authToken", ApiConstants.token)
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }
        AndroidNetworking.post(ApiConstants.getSettings_api)
            .addJSONObjectBody(jsonObject)
            .setTag("getSettings")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(SettingsModel::class.java, object : ParsedRequestListener<SettingsModel> {
                override fun onResponse(response: SettingsModel) {
                    Log.d(TAG, "onResponse: $response")
                    if (response.is_blocked) {
                        Utils.showMessageDialog(
                            this@SplashActivity,
                            "Account Blocked",
                            "Your Account Has Been Blocked, You Cannot Use The App",
                            false
                        ) {
                            it.dismiss()
                            finish()
                        }
                        return
                    }
                    handleAppSettings(response.getAppSettingsDecrypted()!!)
                    startActivity(Intent(this@SplashActivity, LoginActivity::class.java))
                    finish()
                }

                override fun onError(anError: ANError) {
                    Log.d(Variables.TAG, "onError: " + anError.errorBody)
                    Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                    Toast.makeText(
                        this@SplashActivity,
                        "We Are Unable To Import Settings From Server",
                        Toast.LENGTH_SHORT
                    ).show()
                }
            })
    }

    private fun handleAppSettings(response: SettingsModel.SettingsData) {
        Variables.settingsModel = response
        OneSignal.Debug.logLevel = LogLevel.VERBOSE
        OneSignal.initWithContext(this, Variables.settingsModel.onesignal_app_id)

    }

    private fun loginUser() {
        val jsonObject = JSONObject()

        try {
            jsonObject.put("phone_number", SharedPrefsManager.getPhoneNumber(this@SplashActivity))
            jsonObject.put("password", SharedPrefsManager.getPassword(this@SplashActivity))
        } catch (e: JSONException) {
            Log.d(TAG, "getData: " + e.message)
        }
        AndroidNetworking.post(ApiConstants.loginUser_api)
            .addHeaders(Utils.getRequestHeader(this@SplashActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("loginUser")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(DefaultModel::class.java, object : ParsedRequestListener<DefaultModel> {
                override fun onResponse(response: DefaultModel) {
                    if (response.getMessageDecrypted()!!.toLowerCase().contains("success")) {
                        if (response.is_blocked) {
                            Utils.showMessageDialog(
                                this@SplashActivity,
                                "Account Blocked",
                                "Your Account Has Been Blocked, You Cannot Use The App",
                                false
                            ) {
                                it.dismiss()
                                finish()
                            }
                            return
                        }
                        handleAppSettings(response.getAppSettingsDecrypted()!!)
                        try {
                            val data = JSONObject(response.getDataDecrypted()!!)
                            SharedPrefsManager.setLoginStatus(this@SplashActivity, true)
                            SharedPrefsManager.setUid(
                                this@SplashActivity,
                                data.getString("uid")
                            )
                            SharedPrefsManager.setPassword(
                                this@SplashActivity,
                                data.getString("password_decrypted")
                            )
                            SharedPrefsManager.setUsername(
                                this@SplashActivity,
                                data.getString("username")
                            )
                            SharedPrefsManager.setPhoneNumber(
                                this@SplashActivity,
                                data.getString("phone_number")
                            )
                            SharedPrefsManager.setToken(
                                this@SplashActivity,
                                ApiConstants.AUTH + response.getTokenDecrypted()!!
                                    .replace(data.getString("token"), "")
                            )
                            startActivity(Intent(this@SplashActivity, MainActivity::class.java))
                            finish()
                        } catch (e: JSONException) {
                            Log.d(TAG, "onResponse: " + e.message)
                            startActivity(Intent(this@SplashActivity, LoginActivity::class.java))
                            finish()
                        }
                    } else {
                        SharedPrefsManager.setLoginStatus(this@SplashActivity, false)
                        startActivity(Intent(this@SplashActivity, LoginActivity::class.java))
                        finish()
                    }
                }

                override fun onError(anError: ANError) {
                    Log.d(Variables.TAG, "onError: " + anError.errorBody)
                    Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                    Toast.makeText(this@SplashActivity, "Some Error Occurred", Toast.LENGTH_SHORT)
                        .show()
                }
            })
    }

    private fun checkAppCloning() {
        val path = filesDir.path
        if (path.contains(DUAL_APP_ID_999)) {
            killProcess()
        } else {
            val count = getDotCount(path)
            if (count > APP_PACKAGE_DOT_COUNT) {
                killProcess()
            }
        }
    }

    private fun getDotCount(path: String): Int {
        var count = 0
        for (i in 0 until path.length) {
            if (count > APP_PACKAGE_DOT_COUNT) {
                break
            }
            if (path[i] == DOT) {
                count++
            }
        }
        return count
    }

    private fun killProcess() {
        finish()
        Process.killProcess(Process.myPid())
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }
}