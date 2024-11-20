package com.devshiv.expertinstitute.fragments

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.recyclerview.widget.LinearLayoutManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.activities.PlayVideoActivity
import com.devshiv.expertinstitute.adapter.RecordedVideosAdapter
import com.devshiv.expertinstitute.databinding.FragmentLiveVideosBinding
import com.devshiv.expertinstitute.model.YoutubeVideosModel
import com.devshiv.expertinstitute.utils.*
import org.json.JSONException
import org.json.JSONObject

class LiveVideosFragment : Fragment(), MyOnClickListener {

    lateinit var binding: FragmentLiveVideosBinding
    var adapter: RecordedVideosAdapter? = null
    var dataList: ArrayList<YoutubeVideosModel.YoutubeVideosData> = ArrayList()

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentLiveVideosBinding.inflate(layoutInflater)

        return binding.root
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
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getUpcomingLiveVideosData_api)
            .addHeaders(Utils.getRequestHeader(requireActivity()))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getUpcomingLiveVideosData")
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
                            binding.nothingFoundContainer.visibility = View.GONE
                            if (adapter == null) {
                                adapter = RecordedVideosAdapter(
                                    requireActivity(),
                                    dataList,
                                    this@LiveVideosFragment
                                )
                                binding.recyclerView.layoutManager =
                                    LinearLayoutManager(requireActivity())
                                binding.recyclerView.adapter = adapter
                            } else {
                                adapter!!.notifyDataSetChanged()
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

    override fun onClick(data: Any) {
        val pos = data as Int
        val intent = Intent(requireActivity(), PlayVideoActivity::class.java)
        intent.putExtra("data", dataList[pos])
        startActivity(intent)
    }

    companion object {
        fun newInstance(): LiveVideosFragment {
            return LiveVideosFragment()
        }
    }
}