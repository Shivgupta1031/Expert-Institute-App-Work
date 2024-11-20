package com.devshiv.expertinstitute.activities

import carbon.dialog.ProgressDialog
import android.content.Intent
import android.os.Bundle
import android.text.TextUtils
import android.util.Log
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.daimajia.androidanimations.library.Techniques
import com.daimajia.androidanimations.library.YoYo
import com.devshiv.expertinstitute.databinding.ActivityLoginBinding
import com.devshiv.expertinstitute.model.DefaultModel
import com.devshiv.expertinstitute.utils.ApiConstants
import com.devshiv.expertinstitute.utils.SharedPrefsManager
import com.devshiv.expertinstitute.utils.Utils
import com.devshiv.expertinstitute.utils.Utils.Companion.transparentStatusBar
import com.devshiv.expertinstitute.utils.Variables
import org.json.JSONException
import org.json.JSONObject

class LoginActivity : AppCompatActivity(), View.OnClickListener {

    lateinit var binding: ActivityLoginBinding
    var dialog: ProgressDialog? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        window.transparentStatusBar()

        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        dialog = ProgressDialog(this)
        dialog?.setText("Please Wait..")
        dialog?.setCancelable(true)
        dialog?.setCanceledOnTouchOutside(false)

        binding.backBtn.setOnClickListener(this)
        binding.signInBtn.setOnClickListener(this)
        binding.createNewAccountTxt.setOnClickListener(this)
        binding.forgotPassTxt.setOnClickListener(this)

        binding.topContainer.post{
            YoYo.with(Techniques.FadeInDown)
                .duration(400)
                .playOn(binding.topContainer)

            YoYo.with(Techniques.SlideInUp)
                .duration(600)
                .playOn(binding.headlineTxt)

            YoYo.with(Techniques.SlideInUp)
                .duration(800)
                .playOn(binding.taglineTxt)
        }
    }

    override fun onClick(view: View?) {
        when (view) {
            binding.signInBtn -> {
                if (validateData()) {
                    loginUser()
                }
            }
            binding.createNewAccountTxt -> {
                val intent = Intent(this, SignUpActivity::class.java)
                startActivity(intent)
            }
            binding.backBtn -> {
                onBackPressed()
            }
            binding.forgotPassTxt -> {
                handleForgotPassRequest()
            }
        }
    }

    private fun handleForgotPassRequest() {

        if (TextUtils.isEmpty(binding.phoneNumberET.text)) {
            binding.phoneNumberET.error = "Please Enter Phone Number"
            return
        } else if (binding.phoneNumberET.text.toString().length != 10) {
            binding.phoneNumberET.error = "Phone Number Should Be Of 10 Digits"
            return
        }

        val intent = Intent(this@LoginActivity, OtpVerificationActivity::class.java)
        intent.putExtra("username", "")
        intent.putExtra("number", binding.phoneNumberET.text.toString().trim())
        intent.putExtra("password", binding.passwordET.text.toString())
        intent.putExtra("isForgotPass", true)
        startActivity(intent)
    }

    private fun loginUser() {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("phone_number", binding.phoneNumberET.text.toString())
            jsonObject.put("password", binding.passwordET.text.toString())
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.loginUser_api)
            .addHeaders(Utils.getRequestHeader(this@LoginActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("loginUser")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(DefaultModel::class.java, object : ParsedRequestListener<DefaultModel> {
                override fun onResponse(response: DefaultModel) {
                    Utils.cancelLoading()
                    if (response.getMessageDecrypted()!!.toLowerCase().contains("success")) {
                        try {
                            val data = JSONObject(response.getDataDecrypted()!!)
                            SharedPrefsManager.setLoginStatus(this@LoginActivity, true)
                            SharedPrefsManager.setUid(
                                this@LoginActivity,
                                data.getString("uid")
                            )
                            SharedPrefsManager.setPassword(
                                this@LoginActivity,
                                data.getString("password_decrypted")
                            )
                            SharedPrefsManager.setUsername(
                                this@LoginActivity,
                                data.getString("username")
                            )
                            SharedPrefsManager.setPhoneNumber(
                                this@LoginActivity,
                                data.getString("phone_number")
                            )
                            SharedPrefsManager.setToken(
                                this@LoginActivity,
                                ApiConstants.AUTH + response.getTokenDecrypted()!!
                                    .replace(data.getString("token"), "")
                            )
                            startActivity(Intent(this@LoginActivity, MainActivity::class.java))
                            finish()
                        } catch (e: JSONException) {
                            Log.d(Variables.TAG, "onResponse: " + e.message)
                            Utils.showMessageDialog(
                                this@LoginActivity,
                                "Login Failed",
                                "Some Error Occurred, Please Try Again Later",
                                true
                            ) { p0 -> p0?.dismiss() }
                        }
                    } else {
                        Utils.showMessageDialog(
                            this@LoginActivity,
                            "Login Failed",
                            response.getMessageDecrypted(),
                            true
                        ) { p0 -> p0?.dismiss() }
                    }
                }

                override fun onError(anError: ANError) {
                    Log.d(Variables.TAG, "onError: " + anError.errorBody)
                    Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                    Utils.showMessageDialog(
                        this@LoginActivity,
                        "Login Failed",
                        "Some Error Occurred, Please Try Again Later",
                        true
                    ) { p0 -> p0?.dismiss() }
                }
            })
    }

    private fun validateData(): Boolean {
        if (TextUtils.isEmpty(binding.phoneNumberET.text)) {
            binding.phoneNumberET.error = "Please Enter Phone Number"
            return false
        } else if (binding.phoneNumberET.text.toString().length != 10) {
            binding.phoneNumberET.error = "Phone Number Should Be Of 10 Digits"
            return false
        } else if (TextUtils.isEmpty(binding.passwordET.text)) {
            binding.passwordET.error = "Please Enter Password"
            return false
        } else if (binding.passwordET.text.toString().length < 6) {
            binding.passwordET.error = "Password Should Be Of Atleast 6 Characters"
            return false
        }
        return true
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }

}