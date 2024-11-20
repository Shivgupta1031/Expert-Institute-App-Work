package com.devshiv.expertinstitute.activities

import android.app.ProgressDialog
import android.content.Intent
import android.os.Bundle
import android.text.TextUtils
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import com.daimajia.androidanimations.library.Techniques
import com.daimajia.androidanimations.library.YoYo
import com.devshiv.expertinstitute.databinding.ActivitySignUpBinding
import com.devshiv.expertinstitute.utils.Utils.Companion.transparentStatusBar

class SignUpActivity : AppCompatActivity(), View.OnClickListener {

    lateinit var binding: ActivitySignUpBinding
    var dialog: ProgressDialog? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        window.transparentStatusBar()

        binding = ActivitySignUpBinding.inflate(layoutInflater)
        setContentView(binding.root)

        dialog = ProgressDialog(this)
        dialog?.setMessage("Please Wait..")
        dialog?.setCancelable(true)
        dialog?.setProgressStyle(ProgressDialog.STYLE_SPINNER)
        dialog?.setCanceledOnTouchOutside(false)

        binding.sendOtpBtn.setOnClickListener(this)
        binding.alreadyHaveAnAccountTxt.setOnClickListener(this)
        binding.backBtn.setOnClickListener(this)

        binding.topContainer.post {
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
            binding.sendOtpBtn -> {
                if (validateData()) {
                    binding.usernameET.setText(
                        binding.usernameET.text.toString().replace(" ", "").lowercase()
                    )
                    val intent = Intent(this@SignUpActivity, OtpVerificationActivity::class.java)
                    intent.putExtra("username", binding.usernameET.text.toString().trim())
                    intent.putExtra("number", binding.phoneNumberET.text.toString().trim())
                    intent.putExtra("password", binding.passwordET.text.toString())
                    intent.putExtra("isForgotPass", false)
                    startActivity(intent)
                }
            }

            binding.alreadyHaveAnAccountTxt -> {
                val intent = Intent(this, LoginActivity::class.java)
                startActivity(intent)
                finish()
            }

            binding.backBtn -> {
                onBackPressed()
            }
        }
    }

    private fun validateData(): Boolean {
        if (TextUtils.isEmpty(binding.usernameET.text)) {
            binding.phoneNumberET.error = "Please Enter Username"
            return false
        } else if (TextUtils.isEmpty(binding.phoneNumberET.text)) {
            binding.phoneNumberET.error = "Please Enter Phone Number"
            return false
        } else if (binding.phoneNumberET.text.toString().length != 10) {
            binding.phoneNumberET.error = "Phone Number Should Be Of 10 Digits"
            return false
        } else if (TextUtils.isEmpty(binding.passwordET.text)) {
            binding.passwordET.error = "Please Enter Password"
            return false
        } else if (binding.passwordET.text.toString().contains(" ")) {
            binding.passwordET.error = "Please Remove Whitespaces From Password"
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