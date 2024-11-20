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
import com.devshiv.expertinstitute.adapter.MTCategoryAdapter
import com.devshiv.expertinstitute.databinding.ActivityMockTestCategoryBinding
import com.devshiv.expertinstitute.model.MockTestCategoryModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class MockTestCategoryActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityMockTestCategoryBinding
    var adapter: MTCategoryAdapter? = null
    var dataList: ArrayList<MockTestCategoryModel.MockTestCategoryData> = ArrayList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityMockTestCategoryBinding.inflate(layoutInflater)
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

        AndroidNetworking.post(ApiConstants.getMockTestCategoryData_api)
            .addHeaders(Utils.getRequestHeader(this@MockTestCategoryActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getMockTestCategoryData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                MockTestCategoryModel::class.java,
                object : ParsedRequestListener<MockTestCategoryModel> {
                    override fun onResponse(response: MockTestCategoryModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = MTCategoryAdapter(
                                    this@MockTestCategoryActivity,
                                    dataList,
                                    this@MockTestCategoryActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@MockTestCategoryActivity)
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
            val intent = Intent(this@MockTestCategoryActivity, MockTestActivity::class.java)
            intent.putExtra("type", 0)
            intent.putExtra("course_id", 0)
            intent.putExtra("test_category_id", dataList[pos].id)
            startActivity(intent)
        } else {
            val intent = Intent(this@MockTestCategoryActivity, PaymentActivity::class.java)
            intent.putExtra("id", dataList[pos].id)
            intent.putExtra("title", dataList[pos].title)
            intent.putExtra("price", dataList[pos].price)
            intent.putExtra("type", Variables.MOCK_TEST_CATEGORY_ORDER)
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