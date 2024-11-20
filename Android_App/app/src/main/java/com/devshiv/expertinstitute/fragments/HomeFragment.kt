package com.devshiv.expertinstitute.fragments

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.webkit.URLUtil
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.activities.*
import com.devshiv.expertinstitute.adapter.BannersAdapter
import com.devshiv.expertinstitute.databinding.FragmentHomeBinding
import com.devshiv.expertinstitute.model.BannerModel
import com.devshiv.expertinstitute.model.BannerModel.BannerData
import com.devshiv.expertinstitute.model.PCourseModel
import com.devshiv.expertinstitute.utils.*
import com.smarteist.autoimageslider.IndicatorView.animation.type.IndicatorAnimationType
import com.smarteist.autoimageslider.SliderAnimations
import org.json.JSONException
import org.json.JSONObject

class HomeFragment : Fragment(), View.OnClickListener, MyOnClickListener {

    lateinit var binding: FragmentHomeBinding
    var bannersList: ArrayList<BannerData>? = ArrayList()
    var bannersAdapter: BannersAdapter? = null

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentHomeBinding.inflate(layoutInflater)

        getBanners()

        binding.paidCoursesBtn.setOnClickListener(this)
        binding.freeClassesBtn.setOnClickListener(this)
        binding.ebookBtn.setOnClickListener(this)
        binding.mockTestBtn.setOnClickListener(this)
        binding.syllabusBtn.setOnClickListener(this)
        binding.whatsappBtn.setOnClickListener(this)

        binding.imageSlider.isSaveEnabled = false

        return binding.root
    }

    override fun onClick(v: View) {
        when (v.id) {
            R.id.paidCoursesBtn -> {
                val intent = Intent(requireActivity(), PaidCoursesActivity::class.java)
                startActivity(intent)
            }
            R.id.ebookBtn -> {
                val intent = Intent(requireActivity(), EbooksActivity::class.java)
                startActivity(intent)
            }
            R.id.freeClassesBtn -> {
                val intent = Intent(requireActivity(), VideosCategoryActivity::class.java)
                intent.putExtra("course_id", 0)
                startActivity(intent)
            }
            R.id.mockTestBtn -> {
                val intent = Intent(requireActivity(), MockTestCategoryActivity::class.java)
                startActivity(intent)
            }
            R.id.syllabusBtn -> {
                val intent = Intent(requireActivity(), SyllabusListActivity::class.java)
                startActivity(intent)
            }
            R.id.whatsappBtn -> {
                val intent = Intent(Intent.ACTION_VIEW)
                intent.data =
                    Uri.parse("http://api.whatsapp.com/send?phone=${Variables.settingsModel.whatsapp_number}&text=")
                startActivity(intent)
            }
        }
    }

    private fun getBanners() {
        AndroidNetworking.post(ApiConstants.getBanners_api)
            .addHeaders(Utils.getRequestHeader(requireActivity()))
            .setTag("getBanners")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(BannerModel::class.java, object : ParsedRequestListener<BannerModel> {
                override fun onResponse(response: BannerModel) {
                    bannersList!!.addAll(response.getDataDecrypted()!!)
                    setupBanners()
                }

                override fun onError(anError: ANError) {
                    Log.d(Variables.TAG, "onError: " + anError.errorBody)
                    Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                    binding.topBanner.visibility = View.GONE
                }
            })
    }

    private fun setupBanners() {
        if (bannersList != null && bannersList!!.isNotEmpty()) {
            bannersAdapter = BannersAdapter(requireActivity(), bannersList!!, this)
            binding.imageSlider.setIndicatorAnimation(IndicatorAnimationType.SWAP) //set indicator animation by using IndicatorAnimationType. :WORM or THIN_WORM or COLOR or DROP or FILL or NONE or SCALE or SCALE_DOWN or SLIDE and SWAP!!
            binding.imageSlider.setSliderTransformAnimation(SliderAnimations.SIMPLETRANSFORMATION)
            binding.imageSlider.scrollTimeInSec = 2
            binding.imageSlider.setInfiniteAdapterEnabled(false)
            binding.imageSlider.isAutoCycle = true
            binding.imageSlider.setSliderAdapter(bannersAdapter!!)
            binding.imageSlider.startAutoCycle()
        }
    }

    companion object {
        fun newInstance(): HomeFragment {
            return HomeFragment()
        }
    }

    override fun onClick(data: Any) {
        val position = data as Int
        if (bannersList!![position].type == 1) {
            openCourse(bannersList!![position].url!!)
        } else {
            if (bannersList!![position].url != null && URLUtil.isValidUrl(
                    bannersList!![position].url
                )
            ) {
                val intent = Intent(Intent.ACTION_VIEW)
                intent.data = Uri.parse(bannersList!![position].url)
                startActivity(intent)
            }
        }
    }

    private fun openCourse(course_id: String) {
        Utils.showLoading(requireActivity(), false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(requireActivity()))
            jsonObject.put("course_id", course_id)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getPaidCourse_api)
            .addHeaders(Utils.getRequestHeader(requireActivity()))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getPaidCourse")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                PCourseModel::class.java,
                object : ParsedRequestListener<PCourseModel> {
                    override fun onResponse(response: PCourseModel) {
                        Utils.cancelLoading()
                        if (response.getMessageDecrypted()!!
                                .isEmpty() || response.getMessageDecrypted()!!
                                .contains("success", true)
                        ) {
                            val intent =
                                Intent(requireActivity(), CourseDetailsActivity::class.java)
                            intent.putExtra("data", response.getDataDecrypted()!!)
                            startActivity(intent)
                        } else {
                            Toast.makeText(
                                requireActivity(),
                                "${response.getMessageDecrypted()!!}",
                                Toast.LENGTH_SHORT
                            ).show()
                        }
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        Utils.cancelLoading()
                        Toast.makeText(
                            requireActivity(),
                            "Unable To Open This Course",
                            Toast.LENGTH_SHORT
                        ).show()
                    }
                })
    }
}