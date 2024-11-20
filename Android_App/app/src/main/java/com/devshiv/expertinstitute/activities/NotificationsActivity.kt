package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.net.Uri
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.webkit.URLUtil
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.NotificationsAdapter
import com.devshiv.expertinstitute.databinding.ActivityNotificationsBinding
import com.devshiv.expertinstitute.model.NotificationsModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class NotificationsActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityNotificationsBinding
    var adapter: NotificationsAdapter? = null
    var dataList: ArrayList<NotificationsModel.NotificationsData> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityNotificationsBinding.inflate(layoutInflater)
        setContentView(binding.root)

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
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getNotificationsData_api)
            .addHeaders(Utils.getRequestHeader(this@NotificationsActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getNotificationsData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                NotificationsModel::class.java,
                object : ParsedRequestListener<NotificationsModel> {
                    override fun onResponse(response: NotificationsModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = NotificationsAdapter(
                                    this@NotificationsActivity,
                                    dataList,
                                    this@NotificationsActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@NotificationsActivity)
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
        val position = data as Int
        if (dataList[position].click_url != null && URLUtil.isValidUrl(
                dataList[position].click_url
            )
        ) {
            val intent = Intent(Intent.ACTION_VIEW)
            intent.data = Uri.parse(dataList[position].click_url)
            startActivity(intent)
        }
    }
}