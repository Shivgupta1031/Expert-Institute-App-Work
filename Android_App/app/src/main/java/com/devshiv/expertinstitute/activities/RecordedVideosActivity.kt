package com.devshiv.expertinstitute.activities

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.RecordedVideosAdapter
import com.devshiv.expertinstitute.databinding.ActivityRecordedVideosBinding
import com.devshiv.expertinstitute.model.YoutubeVideosModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class RecordedVideosActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityRecordedVideosBinding
    var adapter: RecordedVideosAdapter? = null
    var dataList: ArrayList<YoutubeVideosModel.YoutubeVideosData> = ArrayList()
    var course_id = 0
    var category_id = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityRecordedVideosBinding.inflate(layoutInflater)
        setContentView(binding.root)

        course_id = intent.getIntExtra("course_id", 0)
        category_id = intent.getIntExtra("category_id", 0)

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
            jsonObject.put("category_id", category_id)
            jsonObject.put("video_type", 0)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getVideosData_api)
            .addHeaders(Utils.getRequestHeader(this@RecordedVideosActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getVideosData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                YoutubeVideosModel::class.java,
                object : ParsedRequestListener<YoutubeVideosModel> {
                    override fun onResponse(response: YoutubeVideosModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = RecordedVideosAdapter(
                                    this@RecordedVideosActivity,
                                    dataList,
                                    this@RecordedVideosActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@RecordedVideosActivity)
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
        val intent = Intent(this@RecordedVideosActivity, PlayVideoActivity::class.java)
        intent.putExtra("data", dataList[pos])
        startActivity(intent)
    }
}