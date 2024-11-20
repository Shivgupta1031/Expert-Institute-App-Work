package com.devshiv.expertinstitute.activities

import android.os.Bundle
import android.util.Log
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.SyllabusAdapter
import com.devshiv.expertinstitute.databinding.ActivitySyllabusListBinding
import com.devshiv.expertinstitute.model.SyllabusModel
import com.devshiv.expertinstitute.utils.*
import com.rajat.pdfviewer.PdfViewerActivity
import org.json.JSONException
import org.json.JSONObject

class SyllabusListActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivitySyllabusListBinding
    var adapter: SyllabusAdapter? = null
    var dataList: ArrayList<SyllabusModel.SyllabusData> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivitySyllabusListBinding.inflate(layoutInflater)
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

        AndroidNetworking.post(ApiConstants.getSyllabusData_api)
            .addHeaders(Utils.getRequestHeader(this@SyllabusListActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getSyllabusData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                SyllabusModel::class.java,
                object : ParsedRequestListener<SyllabusModel> {
                    override fun onResponse(response: SyllabusModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = SyllabusAdapter(
                                    this@SyllabusListActivity,
                                    dataList,
                                    this@SyllabusListActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@SyllabusListActivity)
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
        startActivity(
            PdfViewerActivity.launchPdfFromUrl(
                this,
                dataList[pos].file,
                dataList[pos].title,
                "",
                enableDownload = false
            )
        )
    }
}