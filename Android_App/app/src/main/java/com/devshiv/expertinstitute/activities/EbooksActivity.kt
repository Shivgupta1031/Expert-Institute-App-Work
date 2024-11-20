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
import com.devshiv.expertinstitute.adapter.EbooksAdapter
import com.devshiv.expertinstitute.databinding.ActivityEbooksBinding
import com.devshiv.expertinstitute.model.EbooksModel
import com.devshiv.expertinstitute.utils.*
import com.rajat.pdfviewer.PdfViewerActivity
import org.json.JSONException
import org.json.JSONObject

class EbooksActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityEbooksBinding
    var adapter: EbooksAdapter? = null
    var dataList: ArrayList<EbooksModel.EbookData> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityEbooksBinding.inflate(layoutInflater)
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

        AndroidNetworking.post(ApiConstants.getEbooksData_api)
            .addHeaders(Utils.getRequestHeader(this@EbooksActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getEbooksData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                EbooksModel::class.java,
                object : ParsedRequestListener<EbooksModel> {
                    override fun onResponse(response: EbooksModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = EbooksAdapter(
                                    this@EbooksActivity,
                                    dataList,
                                    this@EbooksActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@EbooksActivity)
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
        if (dataList[pos].is_purchased) {
            startActivity(
                PdfViewerActivity.launchPdfFromUrl(
                    this,
                    dataList[pos].file,
                    dataList[pos].title,
                    "",
                    enableDownload = false
                )
            )
        } else {
            val intent = Intent(this@EbooksActivity, PaymentActivity::class.java)
            intent.putExtra("id", dataList[pos].id)
            intent.putExtra("title", dataList[pos].title)
            intent.putExtra("price", dataList[pos].price)
            intent.putExtra("type", Variables.EBOOK_ORDER)
            activityResultLauncher.launch(intent)
        }
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