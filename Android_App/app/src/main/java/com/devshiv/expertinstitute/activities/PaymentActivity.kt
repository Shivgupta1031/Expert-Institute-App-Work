package com.devshiv.expertinstitute.activities

import android.R.attr.label
import android.R.attr.text
import android.content.ClipData
import android.content.ClipboardManager
import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.JSONObjectRequestListener
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.ActivityPaymentBinding
import com.devshiv.expertinstitute.model.*
import com.devshiv.expertinstitute.utils.ApiConstants
import com.devshiv.expertinstitute.utils.SharedPrefsManager
import com.devshiv.expertinstitute.utils.Utils
import com.devshiv.expertinstitute.utils.Variables
import com.devshiv.expertinstitute.utils.Variables.TAG
import com.razorpay.Checkout
import com.razorpay.PaymentData
import com.razorpay.PaymentResultWithDataListener
import org.json.JSONException
import org.json.JSONObject


class PaymentActivity : AppCompatActivity(), PaymentResultWithDataListener {

    lateinit var binding: ActivityPaymentBinding
    var dataList: ArrayList<CouponModel.CouponData> = ArrayList()

    var title: String? = null
    var id: Int = 0
    var type: Int = 0
    var orderID = ""
    var discountAmount: Int = 0
    var coupon_used: Int = 0
    var payment_success = false
    var payAmount: Int = 0
    private val B2B_PG_REQUEST_CODE = 777
    private var defaultAmount = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        if (Variables.settingsModel.payment_method == 0) {
            Checkout.preload(applicationContext)
        }

        binding = ActivityPaymentBinding.inflate(layoutInflater)
        setContentView(binding.root)

        title = intent.getStringExtra("title")
        id = intent.getIntExtra("id", 0)
        payAmount = intent.getIntExtra("price", 0)
        defaultAmount = intent.getIntExtra("price", 0)
        type = intent.getIntExtra("type", Variables.PAID_COURSE_ORDER)

        if (title == null || id == 0 || defaultAmount == 0) {
            Toast.makeText(this, "Failed To Start Payment!", Toast.LENGTH_SHORT).show()
            finish()
        }

        when (type) {
            Variables.PAID_COURSE_ORDER -> {
                binding.titleTxt.text = "Course : $title"
            }

            Variables.EBOOK_ORDER -> {
                binding.titleTxt.text = "Ebook : $title"
            }

            Variables.MOCK_TEST_CATEGORY_ORDER -> {
                binding.titleTxt.text = "Mock Test : $title"
            }

            else -> {
                binding.titleTxt.text = "$title"
            }
        }


        binding.itemPriceTxt.text = "₹$defaultAmount.0"
        binding.discountTxt.text = "₹$discountAmount.0"
        binding.payableAmountTxt.text = "₹$payAmount.0"

        binding.upiTxt.text = Variables.settingsModel.upi_manual_pay
        binding.upiTxt.setOnClickListener {
            val clipboard: ClipboardManager =
                getSystemService(Context.CLIPBOARD_SERVICE) as ClipboardManager
            val clip = ClipData.newPlainText("UPI", binding.upiTxt.text)
            clipboard.setPrimaryClip(clip)
            Toast.makeText(this, "UPI Copied", Toast.LENGTH_SHORT).show()
        }

        if (Variables.settingsModel.payment_method == 2) {
            binding.manualPaymentLayout.visibility = View.VISIBLE
            binding.makePaymentBtn.visibility = View.GONE
        } else {
            binding.manualPaymentLayout.visibility = View.GONE
            binding.makePaymentBtn.visibility = View.VISIBLE
        }

        binding.makePaymentBtn.setOnClickListener {
//            if (SharedPrefsManager.getUsername(this@PaymentActivity)?.isEmpty() == true) {
//                Toast.makeText(this, "Please Complete Your Profile First!", Toast.LENGTH_SHORT)
//                    .show()
//            } else
            if (payAmount == 0) {
                Toast.makeText(
                    this,
                    "Please Select The Plan That You Want To Purchase",
                    Toast.LENGTH_SHORT
                ).show()
            } else {
                when (Variables.settingsModel.payment_method) {
                    0 -> {
                        startRazorPayPaymentRequest()
                    }

                    1 -> {
                        startPhonePePaymentRequest()
                    }

                    2 -> {
                        binding.manualPaymentLayout.visibility = View.VISIBLE
                        binding.makePaymentBtn.visibility = View.GONE
                    }
                }
            }
        }

        setupManualUPIBtns()

        binding.submitRequestBtn.setOnClickListener {
//            if (SharedPrefsManager.getUsername(this@PaymentActivity)?.isEmpty() == true) {
//                Toast.makeText(this, "Please Complete Your Profile First!", Toast.LENGTH_SHORT)
//                    .show()
//            } else
            if (payAmount == 0) {
                Toast.makeText(
                    this,
                    "Please Select The Plan That You Want To Purchase",
                    Toast.LENGTH_SHORT
                ).show()
            } else if (binding.upiTID.text.isEmpty()) {
                binding.upiTID.error = "* Required"
            } else {
                startManualPaymentRequest()
            }
        }

        binding.applyCouponBtn.setOnClickListener {
            if (payAmount == 0) {
                Toast.makeText(
                    this,
                    "Please Select The Plan That You Want To Purchase",
                    Toast.LENGTH_SHORT
                ).show()
            } else if (binding.couponET.text.isEmpty()) {
                binding.couponET.error = "Enter Code"
            } else {
                binding.couponET.setText(
                    binding.couponET.text.toString().replace(" ", "").uppercase()
                )
                var found = false
                var isOldCode = false
                for (code: CouponModel.CouponData in dataList) {
                    if (code.code == binding.couponET.text.toString()) {
                        if (coupon_used != code.id!!) {
                            discountAmount = code.discount_amount!!
                            coupon_used = code.id!!
                            found = true
                            break
                        } else {
                            isOldCode = true
                            break
                        }
                    }
                }

                if (!isOldCode) {
                    payAmount = defaultAmount
                    if (!found) {
                        discountAmount = 0
                        coupon_used = 0
                        Toast.makeText(this, "Invalid Coupon Code", Toast.LENGTH_SHORT).show()
                        binding.discountTxt.text = "₹$discountAmount.0"
                        binding.payableAmountTxt.text = "₹${payAmount}.0"
                    } else {
                        if (discountAmount >= payAmount) {
                            discountAmount = 0
                            coupon_used = 0
                            binding.discountTxt.text = "₹$discountAmount.0"
                            binding.payableAmountTxt.text = "₹${payAmount}.0"
                            Toast.makeText(
                                this,
                                "This Coupon Code Cannot Be Applied!",
                                Toast.LENGTH_SHORT
                            ).show()
                        } else {
                            payAmount -= discountAmount
                            Toast.makeText(this, "Coupon Code Applied!", Toast.LENGTH_SHORT).show()
                            binding.discountTxt.text = "₹$discountAmount.0"
                            binding.payableAmountTxt.text = "₹${payAmount}.0"
                        }
                    }
                }
            }
        }

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }

        getCouponCodesData()
    }

    private fun setupManualUPIBtns() {
        binding.gPayBtn.setOnClickListener {
            startUpiPayment(
                upiId = Variables.settingsModel.upi_manual_pay,
                name = "",
                amount = "$payAmount.00",
                transactionNote = getString(R.string.app_name),
                upiAppPackage = "com.google.android.apps.nbu.paisa.user" // Google Pay package name
            )
        }

        binding.phonePayBtn.setOnClickListener {
            startUpiPayment(
                upiId = Variables.settingsModel.upi_manual_pay,
                name = "",
                amount = "$payAmount.00",
                transactionNote = getString(R.string.app_name),
                upiAppPackage = "com.phonepe.app" // PhonePe package name
            )
        }

        binding.paytmBtn.setOnClickListener {
            startUpiPayment(
                upiId = Variables.settingsModel.upi_manual_pay,
                name = "",
                amount = "$payAmount.00",
                transactionNote = getString(R.string.app_name),
                upiAppPackage = "net.one97.paytm" // Paytm package name
            )
        }
    }

    private fun startUpiPayment(
        upiId: String,
        name: String,
        amount: String,
        transactionNote: String,
        upiAppPackage: String
    ) {
        if (payAmount == 0) {
            Toast.makeText(
                this,
                "Please Select The Plan That You Want To Purchase",
                Toast.LENGTH_SHORT
            ).show()
            return
        }

        val tid = "TID" + System.currentTimeMillis()
        binding.upiTID.setText(tid)

        val uri = Uri.parse("upi://pay").buildUpon()
            .appendQueryParameter("pa", upiId)
            .appendQueryParameter("pn", name)
            .appendQueryParameter("tn", transactionNote)
            .appendQueryParameter("am", amount)
            .appendQueryParameter("cu", "INR")
            .appendQueryParameter("tr", tid)
            .appendQueryParameter(
                "mc",
                "Gweoin96167004542737"
            )  // Add merchant code (important for merchant transactions)
            .build()

        val intent = Intent(Intent.ACTION_VIEW)
        intent.data = uri
        intent.setPackage(upiAppPackage)

        if (intent.resolveActivity(packageManager) != null) {
            startActivity(intent)
        } else {
            Toast.makeText(this, "UPI app not installed", Toast.LENGTH_SHORT).show()
        }
    }

    private fun startManualPaymentRequest() {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
            jsonObject.put(
                "transaction_id",
                binding.upiTID.text.toString()
            )
            jsonObject.put("amount", payAmount)

            val purchaseData = JSONObject()
            purchaseData.put("details", id)

            purchaseData.put("type", type)
            purchaseData.put("price", payAmount - discountAmount)
            purchaseData.put("coupan_used", coupon_used)
            purchaseData.put("payment_method", 2)
            purchaseData.put(
                "transaction_details",
                "UPI TRANSACTION ID = ${binding.upiTID.text.toString()}"
            )

            jsonObject.put("data", purchaseData.toString())
        } catch (e: JSONException) {
            Log.d(TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.createManualPaymentRequest_api)
            .addHeaders(Utils.getRequestHeader(this))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("createManualPaymentRequest")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(MessageModel::class.java,
                object : ParsedRequestListener<MessageModel> {
                    override fun onResponse(response: MessageModel) {
                        val message = response.getMessageDecrypted()

                        if (message == null) {
                            Utils.showMessageDialog(
                                this@PaymentActivity,
                                "Payment Error",
                                "We Are Unable To Send Request!",
                                false,
                            ) {
                                it.dismiss()
                            }
                            return
                        }
                        if (message.contains("success", true)) {
                            Utils.showMessageDialog(
                                this@PaymentActivity,
                                "Request Received",
                                "$message",
                                false,
                            ) {
                                it.dismiss()
                                val data = Intent()
                                data.putExtra("status", payment_success)
                                data.putExtra("underVerification", true)
                                setResult(RESULT_OK, data)
                                finish()
                            }
                        } else {
                            Utils.showMessageDialog(
                                this@PaymentActivity,
                                "Payment Error",
                                message,
                                false,
                            ) {
                                it.dismiss()
                            }
                        }

                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError?) {
                        Log.d(TAG, "onError: " + anError?.message)
                        Utils.cancelLoading()
                        Toast.makeText(
                            this@PaymentActivity,
                            "Some Error Occurred",
                            Toast.LENGTH_SHORT
                        ).show()
                    }

                })
    }

    private fun startRazorPayPaymentRequest() {
        Toast.makeText(this, "Please Wait...", Toast.LENGTH_SHORT).show()
        val checkout = Checkout()
        checkout.setKeyID(Variables.settingsModel.razorpay_api_key_id)
        checkout.setImage(R.drawable.app_icon)
//        orderID = "order_" + System.currentTimeMillis().toString().substring(0, 12)
        try {
            val options = JSONObject()
            options.put("name", SharedPrefsManager.getUsername(this))
            options.put("theme.color", R.color.colorAccent)
            options.put("currency", "INR")
//            options.put("order_id", orderID)
            options.put("amount", payAmount * 100)

            val retryObj = JSONObject()
            retryObj.put("enabled", true)
            retryObj.put("max_count", 4)
            options.put("retry", retryObj)
            options.put("method", "upi")

            val prefill = JSONObject()
//            prefill.put("email", "")
            prefill.put("contact", SharedPrefsManager.getPhoneNumber(this))

            options.put("prefill", prefill)
            checkout.open(this, options)
        } catch (e: Exception) {
            Log.d(TAG, "startRazorPayPaymentRequest: ${e.message}")
            Toast.makeText(this, "Error in payment: " + e.message, Toast.LENGTH_LONG).show()
        }
    }

    private fun startPhonePePaymentRequest() {

        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
            jsonObject.put("price", payAmount) // 0 = Monthly, 1 = Yearly
            jsonObject.put("app_package", packageName)
            jsonObject.put("intent_type", 2)
        } catch (e: JSONException) {
            Log.d(TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.createPhonePePaymentRequest_api)
            .addHeaders(Utils.getRequestHeader(this))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("createPhonePePaymentRequest")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsJSONObject(object : JSONObjectRequestListener {
                override fun onResponse(response: JSONObject) {
                    Utils.cancelLoading()

                    if (response.getString("success").equals("true")) {

                        orderID =
                            response.getJSONObject("data").getString("merchantTransactionId")

                        val redirectUrl: String =
                            response.getJSONObject("data").getJSONObject("instrumentResponse")
                                .getJSONObject("redirectInfo")
                                .getString("url")

                        val intent = Intent(this@PaymentActivity, PhonePeWVActivity::class.java)
                        intent.putExtra("url", redirectUrl)
                        startActivityForResult(intent, B2B_PG_REQUEST_CODE)

                        binding.makePaymentBtn.visibility = View.GONE
                        binding.checkStatusBtn.visibility = View.VISIBLE
                    } else {
                        Toast.makeText(
                            this@PaymentActivity,
                            "Some Error Occurred",
                            Toast.LENGTH_SHORT
                        ).show()
                    }
                }

                override fun onError(anError: ANError?) {
                    Log.d(TAG, "onError: " + anError?.message)
                    Utils.cancelLoading()
                    Toast.makeText(
                        this@PaymentActivity,
                        "Some Error Occurred",
                        Toast.LENGTH_SHORT
                    ).show()
                }

            })

    }

    private fun getCouponCodesData() {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getCouponCodesData_api)
            .addHeaders(Utils.getRequestHeader(this))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getCouponCodesData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                CouponModel::class.java,
                object : ParsedRequestListener<CouponModel> {
                    override fun onResponse(response: CouponModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError-CouponModel: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError-CouponModel: " + anError.errorDetail)
                        Utils.cancelLoading()
                    }
                })
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if (requestCode == B2B_PG_REQUEST_CODE) {
            val intentExtras = data?.extras
            if (intentExtras != null) {
                val status: String = intentExtras.getString("Status", "FAILED")
                if (status.equals("SUCCESS")) {
                    Toast.makeText(this, "Please Don't Close The App", Toast.LENGTH_SHORT).show()
                    purchaseItem(Variables.PHONE_PE, intentExtras.getString("response", ""))
                } else {
                    Toast.makeText(this, "Status : $status", Toast.LENGTH_SHORT).show()
                }
            }
        } else if (requestCode == Checkout.RZP_REQUEST_CODE) {
            val checkout = Checkout()
            checkout.onActivityResult(requestCode, resultCode, data)
        }
    }

    private fun purchaseItem(payment_method: Int, transaction_details: String) {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
            jsonObject.put("details", id)
            jsonObject.put("type", type)
            jsonObject.put("price", payAmount - discountAmount)
            jsonObject.put("coupan_used", coupon_used)
            jsonObject.put("payment_method", payment_method)
            jsonObject.put("transaction_details", "ORDER ID = $orderID || $transaction_details")
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.purchaseItem_api)
            .addHeaders(Utils.getRequestHeader(this))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("purchaseItem")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                MessageModel::class.java,
                object : ParsedRequestListener<MessageModel> {
                    override fun onResponse(response: MessageModel) {
                        Log.d(TAG, "onResponse: $response")
                        val message = response.getMessageDecrypted()

                        if (message == null) {
                            Utils.showMessageDialog(
                                this@PaymentActivity,
                                "Payment Error",
                                "We Are Unable To Save This Purchase!",
                                false,
                            ) {
                                it.dismiss()
                            }
                            return
                        }
                        if (message.contains("success", true)) {
                            Utils.showMessageDialog(
                                this@PaymentActivity,
                                "Payment Completed",
                                "$message, Thank You, For The Purchase!",
                                false,
                            ) {
                                payment_success = true
                                it.dismiss()
                                onBackPressed()
                            }
                        } else {
                            Utils.showMessageDialog(
                                this@PaymentActivity,
                                "Payment Error",
                                message,
                                false,
                            ) {
                                it.dismiss()
                            }
                        }

                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        Utils.cancelLoading()
                    }
                })
    }

    override fun onPaymentSuccess(razorpayPaymentID: String?, paymentData: PaymentData?) {
        Log.d(TAG, "onPaymentSuccess: $razorpayPaymentID || ${paymentData}")
        Toast.makeText(this, "Please Don't Close The App", Toast.LENGTH_SHORT).show()
        purchaseItem(Variables.RAZORPAY, "$razorpayPaymentID || ${paymentData?.data}")
    }

    override fun onPaymentError(code: Int, response: String?, paymentData: PaymentData?) {
        Log.d(TAG, "onPaymentError: $code || $response || ${paymentData}")
        Toast.makeText(this, "Payment Failed : $response", Toast.LENGTH_SHORT).show()
    }

    override fun onBackPressed() {
        val data = Intent()
        data.putExtra("status", payment_success)
        setResult(RESULT_OK, data)
        finish()
    }

}