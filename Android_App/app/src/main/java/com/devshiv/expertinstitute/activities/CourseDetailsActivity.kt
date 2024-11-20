package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.os.Bundle
import android.text.method.LinkMovementMethod
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DecodeFormat
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.request.RequestOptions
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.ActivityCourseDetailsBinding
import com.devshiv.expertinstitute.model.PaidCourseData
import com.devshiv.expertinstitute.utils.Variables
import com.devshiv.expertinstitute.utils.Variables.TAG
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import net.nightwhistler.htmlspanner.HtmlSpanner

class CourseDetailsActivity : AppCompatActivity() {

    lateinit var binding: ActivityCourseDetailsBinding
    var courseData: PaidCourseData? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityCourseDetailsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        courseData = intent.getSerializableExtra("data") as PaidCourseData

        if (courseData == null) {
            Toast.makeText(this, "Unable To Fetch Details", Toast.LENGTH_SHORT).show()
            finish()
        }

        Glide.with(this).load(courseData!!.image)
            .apply(
                RequestOptions()
                    .centerInside()
                    .diskCacheStrategy(DiskCacheStrategy.RESOURCE)
                    .placeholder(R.drawable.btn_gradient_2)
                    .format(DecodeFormat.PREFER_RGB_565)
            )
            .thumbnail(0.08f)
            .into(binding.image)

        binding.titleTxt.text = courseData!!.title
        binding.priceTxt.text = "${courseData!!.price} INR"

        CoroutineScope(Dispatchers.Main).launch {
            binding.descriptionTxt.text = loadHtmlContent(courseData!!.description!!)
            binding.descriptionTxt.movementMethod = LinkMovementMethod.getInstance()
        }

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }

        if (courseData!!.is_purchased) {
            binding.buyNowBtn.text = "Open"
            binding.priceContainer.visibility = View.GONE
        } else {
            binding.buyNowBtn.text = "Buy Now"
            binding.priceContainer.visibility = View.VISIBLE
        }

        binding.buyNowBtn.setOnClickListener {
            if (courseData!!.is_purchased) {
                val intent = Intent(this@CourseDetailsActivity, CourseOptionsActivity::class.java)
                intent.putExtra("course_id", courseData!!.id)
                startActivity(intent)
            } else {
                val intent = Intent(this@CourseDetailsActivity, PaymentActivity::class.java)
                intent.putExtra("id", courseData!!.id)
                intent.putExtra("title", courseData!!.title)
                intent.putExtra("price", courseData!!.price)
                intent.putExtra("type", Variables.PAID_COURSE_ORDER)
                activityResultLauncher.launch(intent)
            }
        }
    }

    suspend fun loadHtmlContent(description: String): CharSequence {
        return withContext(Dispatchers.IO) {
            HtmlSpanner().fromHtml(description)
        }
    }

    var activityResultLauncher =
        registerForActivityResult(ActivityResultContracts.StartActivityForResult()) { result ->
            Log.d(TAG, "activityResultLauncher: $result ")
            if (result.resultCode == RESULT_OK) {
                if (result.data != null) {
                    val status = result!!.data!!.getBooleanExtra("status", false)
                    if (status) {
                        setResult(RESULT_OK, result.data)
                        finish()
                    }
                }
            }
        }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }
}