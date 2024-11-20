package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.PaidCourseAdapter
import com.devshiv.expertinstitute.databinding.ActivityPaidCoursesBinding
import com.devshiv.expertinstitute.model.PaidCourseData
import com.devshiv.expertinstitute.model.PaidCoursesModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class PaidCoursesActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityPaidCoursesBinding
    var adapter: PaidCourseAdapter? = null
    var dataList: ArrayList<PaidCourseData> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityPaidCoursesBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }

        getData()
    }

    private fun getData() {
        Utils.showLoading(this, false)

        binding.paidCoursesRV.visibility = View.VISIBLE
        binding.nothingFoundTxt.visibility = View.GONE

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getPaidCoursesData_api)
            .addHeaders(Utils.getRequestHeader(this@PaidCoursesActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getPaidCoursesData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(PaidCoursesModel::class.java,
                object : ParsedRequestListener<PaidCoursesModel> {
                    override fun onResponse(response: PaidCoursesModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.paidCoursesRV.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = PaidCourseAdapter(
                                    this@PaidCoursesActivity,
                                    dataList,
                                    this@PaidCoursesActivity
                                )
                                binding.paidCoursesRV.layoutManager =
                                    LinearLayoutManager(this@PaidCoursesActivity)
                                binding.paidCoursesRV.adapter = adapter
                            } else {
                                adapter!!.notifyDataSetChanged()
                            }
                        } else {
                            binding.paidCoursesRV.visibility = View.GONE
                            binding.nothingFoundTxt.visibility = View.VISIBLE
                        }
                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        binding.paidCoursesRV.visibility = View.GONE
                        binding.nothingFoundTxt.visibility = View.VISIBLE
                        Utils.cancelLoading()
                    }
                })
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }

    override fun onClick(data: Any) {
        val pos = data as Int
        val intent = Intent(this@PaidCoursesActivity, CourseDetailsActivity::class.java)
        intent.putExtra("data", dataList[pos])
        activityResultLauncher.launch(intent)
    }

    var activityResultLauncher =
        registerForActivityResult(ActivityResultContracts.StartActivityForResult()) { result ->
            Log.d(Variables.TAG, "activityResultLauncher: $result ")
            if (result.resultCode == RESULT_OK) {
                if (result.data != null) {
                    val status = result!!.data!!.getBooleanExtra("status", false)
                    if (status) {
                        getData()
                    }
                }
            }
        }
}