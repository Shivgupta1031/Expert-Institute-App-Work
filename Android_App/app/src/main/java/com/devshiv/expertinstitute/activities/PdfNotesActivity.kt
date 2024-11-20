package com.devshiv.expertinstitute.activities

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.PDFNotesAdapter
import com.devshiv.expertinstitute.databinding.ActivityPdfNotesBinding
import com.devshiv.expertinstitute.model.PDFNotesModel
import com.devshiv.expertinstitute.utils.*
import com.rajat.pdfviewer.PdfViewerActivity
import org.json.JSONException
import org.json.JSONObject

class PdfNotesActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityPdfNotesBinding
    var adapter: PDFNotesAdapter? = null
    var dataList: ArrayList<PDFNotesModel.PDFNotesData> = ArrayList()
    var course_id: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityPdfNotesBinding.inflate(layoutInflater)
        setContentView(binding.root)

        course_id = intent.getIntExtra("course_id", 0)

        if (course_id == 0) {
            Toast.makeText(this, "Failed To Open Course", Toast.LENGTH_SHORT).show()
            finish()
        }

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

        AndroidNetworking.post(ApiConstants.getPDFNotesData_api)
            .addHeaders(Utils.getRequestHeader(this@PdfNotesActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getPDFNotesData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                PDFNotesModel::class.java,
                object : ParsedRequestListener<PDFNotesModel> {
                    override fun onResponse(response: PDFNotesModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = PDFNotesAdapter(
                                    this@PdfNotesActivity,
                                    dataList,
                                    this@PdfNotesActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@PdfNotesActivity)
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