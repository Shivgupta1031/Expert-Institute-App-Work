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
import com.devshiv.expertinstitute.adapter.MockTestAdapter
import com.devshiv.expertinstitute.databinding.ActivityMockTestBinding
import com.devshiv.expertinstitute.model.MockTestModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class MockTestActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityMockTestBinding
    var adapter: MockTestAdapter? = null
    var dataList: ArrayList<MockTestModel.MockTestData> = ArrayList()
    var test_category_id: Int = 0
    var course_id: Int = 0
    var type: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityMockTestBinding.inflate(layoutInflater)
        setContentView(binding.root)

        type = intent.getIntExtra("type", 0)
        course_id = intent.getIntExtra("course_id", 0)
        test_category_id = intent.getIntExtra("test_category_id", 0)

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
            jsonObject.put("type", type)
            jsonObject.put("course_id", course_id)
            jsonObject.put("test_category_id", test_category_id)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getMockTestData_api)
            .addHeaders(Utils.getRequestHeader(this@MockTestActivity))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getMockTestData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                MockTestModel::class.java,
                object : ParsedRequestListener<MockTestModel> {
                    override fun onResponse(response: MockTestModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundTxt.visibility = View.GONE
                            if (adapter == null) {
                                adapter = MockTestAdapter(
                                    this@MockTestActivity,
                                    dataList,
                                    this@MockTestActivity
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(this@MockTestActivity)
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
        Utils.showAlertDialog(
            this@MockTestActivity,
            "Start Test",
            "This Test Contains ${dataList[pos].questions} Questions. Do You Want To Start The Test Now?",
            true,
            object : MyOnClickListener {
                override fun onClick(data: Any) {
                    val intent = Intent(this@MockTestActivity, QuestionsActivity::class.java)
                    intent.putExtra("mock_test_id", dataList[pos].id)
                    intent.putExtra("test_time", dataList[pos].test_time)
                    startActivity(intent)
                }
            }
        )
    }

}