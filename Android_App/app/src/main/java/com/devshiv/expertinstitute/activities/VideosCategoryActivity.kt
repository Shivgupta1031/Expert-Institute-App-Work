package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.VideosCategoryAdapter
import com.devshiv.expertinstitute.databinding.ActivityVideosCategoryBinding
import com.devshiv.expertinstitute.model.VideosCategoryModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class VideosCategoryActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityVideosCategoryBinding
    var adapter: VideosCategoryAdapter? = null
    var dataList: ArrayList<VideosCategoryModel.VideoCategoryData> = ArrayList()
    var course_id = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityVideosCategoryBinding.inflate(layoutInflater)
        setContentView(binding.root)

        course_id = intent.getIntExtra("course_id", 0)

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }

        getData()
    }

    private fun getData() {
        Utils.showLoading(this, false)

        binding.recyclerView.visibility = View.VISIBLE
        binding.nothingFoundTxt.visibility = View.GONE

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
            jsonObject.put("course_id", course_id)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getVideosCategoryData_api)
            .addHeaders(Utils.getRequestHeader(this@VideosCategoryActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getVideosCategoryData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                VideosCategoryModel::class.java,
                object : ParsedRequestListener<VideosCategoryModel> {
                    override fun onResponse(response: VideosCategoryModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = VideosCategoryAdapter(
                                    this@VideosCategoryActivity,
                                    dataList,
                                    this@VideosCategoryActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@VideosCategoryActivity)
                                binding.recyclerView.adapter = adapter
                            } else {
                                adapter!!.notifyDataSetChanged()
                            }
                        } else {
                            binding.recyclerView.visibility = View.GONE
                            binding.nothingFoundTxt.visibility = View.VISIBLE
                        }
                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        binding.recyclerView.visibility = View.GONE
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
        val intent = Intent(this@VideosCategoryActivity, RecordedVideosActivity::class.java)
        intent.putExtra("course_id", course_id)
        intent.putExtra("category_id", dataList[pos].id)
        startActivity(intent)
    }
}