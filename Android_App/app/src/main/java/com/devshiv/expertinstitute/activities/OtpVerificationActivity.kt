package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.databinding.ActivityOtpVerificationBinding
import com.devshiv.expertinstitute.model.DefaultModel
import com.devshiv.expertinstitute.utils.ApiConstants
import com.devshiv.expertinstitute.utils.SharedPrefsManager
import com.devshiv.expertinstitute.utils.Utils
import com.devshiv.expertinstitute.utils.Variables
import com.devshiv.expertinstitute.utils.Variables.TAG
import com.google.firebase.FirebaseException
import com.google.firebase.FirebaseTooManyRequestsException
import com.google.firebase.auth.*
import com.otpview.OTPListener
import org.json.JSONException
import org.json.JSONObject
import java.util.concurrent.TimeUnit

class OtpVerificationActivity : AppCompatActivity() {

    lateinit var binding: ActivityOtpVerificationBinding
    var verificationID: String? = ""
    var resendToken: PhoneAuthProvider.ForceResendingToken? = null
    var username: String? = ""
    var number: String? = ""
    var password: String? = ""
    var verificationCode: String = ""
    var isForgotPass: Boolean = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityOtpVerificationBinding.inflate(layoutInflater)
        setContentView(binding.root)

        username = intent.getStringExtra("username")
        number = intent.getStringExtra("number")
        password = intent.getStringExtra("password")
        isForgotPass = intent.getBooleanExtra("isForgotPass", false)

        if (username == null || number == null || password == null) {
            Toast.makeText(
                this@OtpVerificationActivity,
                "Details Missing, Please Try Again!",
                Toast.LENGTH_SHORT
            ).show()
            finish()
        }

//        sendOTP()
        signUpNewUser()

        binding.otpHeadTxt.text = "Sending OTP To Phone Number\n+91 ${number}"

        binding.otpET.requestFocusOTP()
        binding.otpET.otpListener = object : OTPListener {
            override fun onInteractionListener() {

            }

            override fun onOTPComplete(otp: String) {
                verificationCode = otp
            }
        }

        binding.verifyOtpBtn.setOnClickListener {
            val credential = PhoneAuthProvider.getCredential(verificationID!!, verificationCode)
            signInWithPhoneAuthCredential(credential)
        }

        binding.resendOtpTxt.setOnClickListener {
            resendOtp()
        }

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }
    }

    private fun sendOTP() {
        Utils.showLoading(this, false)
        binding.resendOtpTxt.visibility = View.GONE

        val number = "+91${number}"
        val options = PhoneAuthOptions.newBuilder(FirebaseAuth.getInstance())
            .setPhoneNumber(number) // Phone number to verify
            .setTimeout(60L, TimeUnit.SECONDS) // Timeout and unit
            .setActivity(this) // Activity (for callback binding)
            .setCallbacks(otpCallback) // OnVerificationStateChangedCallbacks
            .build()
        PhoneAuthProvider.verifyPhoneNumber(options)
    }

    private var otpCallback = object : PhoneAuthProvider.OnVerificationStateChangedCallbacks() {

        override fun onVerificationCompleted(credential: PhoneAuthCredential) {
            Log.d(TAG, "onVerificationCompleted:$credential")
            Utils.cancelLoading()
            val code: String? = credential.smsCode

            if (code != null) {
                binding.otpET.setOTP(code)
                signInWithPhoneAuthCredential(credential)
            }
        }

        override fun onVerificationFailed(e: FirebaseException) {
            // This callback is invoked in an invalid request for verification is made,
            // for instance if the the phone number format is not valid.
            Log.d(TAG, "onVerificationFailed", e)
            Utils.cancelLoading()
            when (e) {
                is FirebaseAuthInvalidCredentialsException -> {
                    Toast.makeText(
                        this@OtpVerificationActivity,
                        "Invalid Phone Number",
                        Toast.LENGTH_SHORT
                    ).show()
                    finish()
                }

                is FirebaseTooManyRequestsException -> {
                    Toast.makeText(
                        this@OtpVerificationActivity,
                        "Too Many OTP Request, Try Again Later",
                        Toast.LENGTH_SHORT
                    ).show()
                }

                else -> {
                    Toast.makeText(
                        this@OtpVerificationActivity,
                        "OTP Verification Failed, Try Again Later",
                        Toast.LENGTH_SHORT
                    ).show()
                }
            }
        }

        override fun onCodeSent(
            verificationId: String,
            token: PhoneAuthProvider.ForceResendingToken,
        ) {
            // The SMS verification code has been sent to the provided phone number, we
            // now need to ask the user to enter the code and then construct a credential
            // by combining the code with a verification ID.
            Log.d(TAG, "onCodeSent:$verificationId")
            Utils.cancelLoading()

            verificationID = verificationId
            resendToken = token

            binding.resendOtpTxt.visibility = View.VISIBLE

            binding.otpHeadTxt.text = "OTP Has Been Successfully Sent To\n+91 ${number}"

            Toast.makeText(
                this@OtpVerificationActivity,
                "OTP Sent Successfully!",
                Toast.LENGTH_SHORT
            ).show()
        }
    }

    private fun signInWithPhoneAuthCredential(credential: PhoneAuthCredential) {
        Utils.showLoading(this, false)
        FirebaseAuth.getInstance()
            .signInWithCredential(credential)
            .addOnCompleteListener(this) { task ->
                Utils.cancelLoading()
                if (task.isSuccessful) {
                    Log.d(TAG, "signInWithCredential:success")
                    val user = task.result?.user
                    binding.resendOtpTxt.visibility = View.GONE
                    if (isForgotPass) {
                        loginUser()
                    } else {
                        signUpNewUser()
                    }
                } else {
                    Log.w(TAG, "signInWithCredential:failure", task.exception)
                    if (task.exception is FirebaseAuthInvalidCredentialsException) {
                        Toast.makeText(
                            this@OtpVerificationActivity,
                            "Incorrect OTP!",
                            Toast.LENGTH_SHORT
                        ).show()
                    }
                }
            }
    }

    private fun loginUser() {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("phone_number", number)
            jsonObject.put("password", "")
            jsonObject.put("isForgetPass", 1)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.loginUser_api)
            .addHeaders(Utils.getRequestHeader(this@OtpVerificationActivity))
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
                            SharedPrefsManager.setLoginStatus(this@OtpVerificationActivity, true)
                            SharedPrefsManager.setUid(
                                this@OtpVerificationActivity,
                                data.getString("uid")
                            )
                            SharedPrefsManager.setPassword(
                                this@OtpVerificationActivity,
                                data.getString("password_decrypted")
                            )
                            SharedPrefsManager.setUsername(
                                this@OtpVerificationActivity,
                                data.getString("username")
                            )
                            SharedPrefsManager.setPhoneNumber(
                                this@OtpVerificationActivity,
                                data.getString("phone_number")
                            )
                            SharedPrefsManager.setToken(
                                this@OtpVerificationActivity,
                                ApiConstants.AUTH + response.getTokenDecrypted()!!
                                    .replace(data.getString("token"), "")
                            )
                            startActivity(
                                Intent(
                                    this@OtpVerificationActivity,
                                    MainActivity::class.java
                                )
                            )
                            finishAffinity()
                        } catch (e: JSONException) {
                            Log.d(Variables.TAG, "onResponse: " + e.message)
                            Utils.showMessageDialog(
                                this@OtpVerificationActivity,
                                "Login Failed",
                                "Some Error Occurred, Please Try Again Later",
                                true
                            ) { p0 -> p0?.dismiss() }
                        }
                    } else {
                        Utils.showMessageDialog(
                            this@OtpVerificationActivity,
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
                        this@OtpVerificationActivity,
                        "Login Failed",
                        "Some Error Occurred, Please Try Again Later",
                        true
                    ) { p0 -> p0?.dismiss() }
                }
            })
    }

    private fun resendOtp() {
        if (resendToken == null) {
            Toast.makeText(this, "Cannot Resend OTP", Toast.LENGTH_SHORT).show()
            binding.resendOtpTxt.visibility = View.GONE
            return
        }
        Utils.showLoading(this, false)

        val number = "+91${number}"
        val options = PhoneAuthOptions.newBuilder(FirebaseAuth.getInstance())
            .setPhoneNumber(number) // Phone number to verify
            .setTimeout(60L, TimeUnit.SECONDS) // Timeout and unit
            .setActivity(this) // Activity (for callback binding)
            .setCallbacks(otpCallback) // OnVerificationStateChangedCallbacks
            .setForceResendingToken(resendToken!!)
            .build()
        PhoneAuthProvider.verifyPhoneNumber(options)
    }

    private fun signUpNewUser() {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("authToken", ApiConstants.token)
            jsonObject.put("username", username)
            jsonObject.put("phone_number", number)
            jsonObject.put("password", password)
            jsonObject.put("device_id", Utils.getDeviceId(this))
            jsonObject.put("token", Utils.getEncryptedDeviceId(this))
        } catch (e: JSONException) {
            Log.d(TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.registerUser_api)
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("registerUser")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(DefaultModel::class.java, object : ParsedRequestListener<DefaultModel> {
                override fun onResponse(response: DefaultModel) {
                    Utils.cancelLoading()
                    if (response.getMessageDecrypted()!!.toLowerCase().contains("success")) {
                        try {
                            val data = JSONObject(response.getDataDecrypted()!!)
                            SharedPrefsManager.setLoginStatus(this@OtpVerificationActivity, true)
                            SharedPrefsManager.setUid(
                                this@OtpVerificationActivity,
                                data.getString("uid")
                            )
                            SharedPrefsManager.setPassword(
                                this@OtpVerificationActivity,
                                data.getString("password_decrypted")
                            )
                            SharedPrefsManager.setUsername(
                                this@OtpVerificationActivity,
                                data.getString("username")
                            )
                            SharedPrefsManager.setPhoneNumber(
                                this@OtpVerificationActivity,
                                data.getString("phone_number")
                            )
                            SharedPrefsManager.setToken(
                                this@OtpVerificationActivity,
                                ApiConstants.AUTH + response.getTokenDecrypted()!!
                                    .replace(data.getString("token"), "")
                            )
                            Utils.showMessageDialog(
                                this@OtpVerificationActivity,
                                "Sign Up",
                                "Welcome ${data.getString("username")}!",
                                true
                            ) { p0 ->
                                p0?.dismiss()
                                startActivity(
                                    Intent(
                                        this@OtpVerificationActivity,
                                        MainActivity::class.java
                                    )
                                )
                                finishAffinity()
                            }
                        } catch (e: JSONException) {
                            Log.d(TAG, "onResponse: " + e.message)
                            Utils.showMessageDialog(
                                this@OtpVerificationActivity,
                                "Sign Up Failed",
                                "Some Error Occurred While Creating Your Account",
                                true
                            ) { p0 ->
                                p0?.dismiss()
                                finish()
                            }
                        }
                    } else {
                        Utils.showMessageDialog(
                            this@OtpVerificationActivity,
                            "Sign Up Failed",
                            response.getMessageDecrypted()!!,
                            true
                        ) { p0 ->
                            p0?.dismiss()
                            finish()
                        }
                    }
                }

                override fun onError(anError: ANError) {
                    Log.d(TAG, "onError: " + anError.errorBody)
                    Log.d(TAG, "onError: " + anError.errorDetail)
                    Utils.cancelLoading()
                    Utils.showMessageDialog(
                        this@OtpVerificationActivity,
                        "Sign Up Failed",
                        "Some Error Occurred While Creating Your Account : " + anError.errorDetail,
                        true
                    ) { p0 ->
                        p0?.dismiss()
                        finish()
                    }
                }
            })
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }
}