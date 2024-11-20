package com.devshiv.expertinstitute.fragments

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.content.ContextCompat
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import carbon.widget.TextView
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.activities.CourseDetailsActivity
import com.devshiv.expertinstitute.activities.MockTestActivity
import com.devshiv.expertinstitute.adapter.*
import com.devshiv.expertinstitute.databinding.FragmentMyOrdersBinding
import com.devshiv.expertinstitute.model.*
import com.devshiv.expertinstitute.utils.*
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import com.rajat.pdfviewer.PdfViewerActivity
import org.json.JSONException
import org.json.JSONObject

class MyOrdersFragment : Fragment(), MyOnClickListener {

    lateinit var binding: FragmentMyOrdersBinding
    var paidCourseAdapter: PaidCourseAdapter? = null
    var mtCategoryAdapter: MTCategoryAdapter? = null
    var ebooksAdapter: EbooksAdapter? = null
    var dataList: ArrayList<Any> = ArrayList()
    var curType: Int = 0

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentMyOrdersBinding.inflate(layoutInflater)

        binding.myCourseTxt.setOnClickListener {
            if (curType != 0) {
                curType = 0
                selectOption(binding.myCourseTxt)
                getData()
            }
        }
        binding.ebooksTxt.setOnClickListener {
            if (curType != 1) {
                curType = 1
                selectOption(binding.ebooksTxt)
                getData()
            }
        }
        binding.mockTestTxt.setOnClickListener {
            if (curType != 2) {
                curType = 2
                selectOption(binding.mockTestTxt)
                getData()
            }
        }

        return binding.root
    }

    private fun selectOption(selectedView: TextView) {
        binding.myCourseTxt.setBackgroundColor(
            ContextCompat.getColor(
                requireActivity(),
                R.color.transparent
            )
        )
        binding.myCourseTxt.setTextColor(
            ContextCompat.getColor(
                requireActivity(),
                carbon.R.color.carbon_grey_800
            )
        )

        binding.ebooksTxt.setBackgroundColor(
            ContextCompat.getColor(
                requireActivity(),
                R.color.transparent
            )
        )
        binding.ebooksTxt.setTextColor(
            ContextCompat.getColor(
                requireActivity(),
                carbon.R.color.carbon_grey_800
            )
        )

        binding.mockTestTxt.setBackgroundColor(
            ContextCompat.getColor(
                requireActivity(),
                R.color.transparent
            )
        )
        binding.mockTestTxt.setTextColor(
            ContextCompat.getColor(
                requireActivity(),
                carbon.R.color.carbon_grey_800
            )
        )

        selectedView.setBackgroundColor(
            ContextCompat.getColor(
                requireActivity(),
                R.color.colorAccent
            )
        )
        selectedView.setTextColor(ContextCompat.getColor(requireActivity(), R.color.white))
    }

    override fun onResume() {
        super.onResume()
        getData()
    }

    private fun getData() {
        Utils.showLoading(requireActivity(), false)

        binding.recyclerView.visibility = View.VISIBLE
        binding.nothingFoundContainer.visibility = View.GONE

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(requireActivity()))
            jsonObject.put("type", curType)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getUserOrdersData_api)
            .addHeaders(Utils.getRequestHeader(requireActivity()))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getUserOrdersData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                DefaultModel::class.java,
                object : ParsedRequestListener<DefaultModel> {
                    override fun onResponse(response: DefaultModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        val data = getDecryptedData(curType, response.data)
                        if (data != null) {
                            dataList.addAll(data as Collection<Any>)
                        }
                        if (dataList.size > 0) {
                            binding.recyclerView.visibility = View.VISIBLE
                            binding.nothingFoundContainer.visibility = View.GONE
                            if (curType == 0) {
                                if (paidCourseAdapter == null) {
                                    paidCourseAdapter = PaidCourseAdapter(
                                        requireActivity(),
                                        dataList as ArrayList<PaidCourseData>,
                                        this@MyOrdersFragment
                                    )
                                    binding.recyclerView.layoutManager =
                                        LinearLayoutManager(requireActivity())
                                    binding.recyclerView.adapter = paidCourseAdapter
                                } else {
                                    binding.recyclerView.adapter = paidCourseAdapter
                                    paidCourseAdapter!!.notifyDataSetChanged()
                                }
                            } else if (curType == 1) {
                                if (ebooksAdapter == null) {
                                    ebooksAdapter = EbooksAdapter(
                                        requireActivity(),
                                        dataList as ArrayList<EbooksModel.EbookData>,
                                        this@MyOrdersFragment
                                    )
                                    binding.recyclerView.layoutManager =
                                        LinearLayoutManager(requireActivity())
                                    binding.recyclerView.adapter = ebooksAdapter
                                } else {
                                    binding.recyclerView.adapter = ebooksAdapter
                                    ebooksAdapter!!.notifyDataSetChanged()
                                }
                            } else if (curType == 2) {
                                if (mtCategoryAdapter == null) {
                                    mtCategoryAdapter = MTCategoryAdapter(
                                        requireActivity(),
                                        dataList as ArrayList<MockTestCategoryModel.MockTestCategoryData>,
                                        this@MyOrdersFragment
                                    )
                                    binding.recyclerView.layoutManager =
                                        LinearLayoutManager(requireActivity())
                                    binding.recyclerView.adapter = mtCategoryAdapter
                                } else {
                                    binding.recyclerView.adapter = mtCategoryAdapter
                                    mtCategoryAdapter!!.notifyDataSetChanged()
                                }
                            }
                        } else {
                            binding.recyclerView.visibility = View.GONE
                            binding.nothingFoundContainer.visibility = View.VISIBLE
                        }
                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        binding.recyclerView.visibility = View.GONE
                        binding.nothingFoundContainer.visibility = View.VISIBLE
                        Utils.cancelLoading()
                    }
                })
    }

    private fun getDecryptedData(dataType: Int, data: String): Any? {
        val gson = Gson()
        val type = when (dataType) {
            0 -> {
                object : TypeToken<ArrayList<PaidCourseData>?>() {}.type
            }
            1 -> {
                object : TypeToken<ArrayList<EbooksModel.EbookData>?>() {}.type
            }
            2 -> {
                object : TypeToken<ArrayList<MockTestCategoryModel.MockTestCategoryData>?>() {}.type
            }
            else -> {
                null
            }
        }
        return gson.fromJson(Utils.decrypt(data), type)
    }

    override fun onClick(data: Any) {
        val pos = data as Int
        when (curType) {
            0 -> {
                val model = dataList[pos] as PaidCourseData
                val intent = Intent(requireActivity(), CourseDetailsActivity::class.java)
                intent.putExtra("data", model)
                startActivity(intent)
            }
            1 -> {
                val model = dataList[pos] as EbooksModel.EbookData
                startActivity(
                    PdfViewerActivity.launchPdfFromUrl(
                        requireActivity(),
                        model.file,
                        model.title,
                        "",
                        enableDownload = false
                    )
                )
            }
            2 -> {
                val model = dataList[pos] as MockTestCategoryModel.MockTestCategoryData
                val intent = Intent(requireActivity(), MockTestActivity::class.java)
                intent.putExtra("type", 0)
                intent.putExtra("course_id", 0)
                intent.putExtra("test_category_id", model.id)
                startActivity(intent)
            }
        }
    }

    companion object {
        fun newInstance(): MyOrdersFragment {
            return MyOrdersFragment()
        }
    }
}