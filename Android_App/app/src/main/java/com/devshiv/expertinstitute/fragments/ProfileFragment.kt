package com.devshiv.expertinstitute.fragments

import android.app.Activity
import android.os.Bundle
import android.text.TextUtils
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.activity.result.ActivityResult
import androidx.activity.result.contract.ActivityResultContracts
import androidx.fragment.app.Fragment
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DecodeFormat
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.request.RequestOptions
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.FragmentProfileBinding
import com.devshiv.expertinstitute.model.UserProfileModel
import com.devshiv.expertinstitute.utils.ApiConstants
import com.devshiv.expertinstitute.utils.SharedPrefsManager
import com.devshiv.expertinstitute.utils.Utils
import com.devshiv.expertinstitute.utils.Variables
import com.github.dhaval2404.imagepicker.ImagePicker
import org.json.JSONException
import org.json.JSONObject
import java.io.File

class ProfileFragment : Fragment() {

    lateinit var binding: FragmentProfileBinding
    var profilePic = ""

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentProfileBinding.inflate(layoutInflater)

        binding.profilePicImg.setOnClickListener {
            ImagePicker.with(this)
                .maxResultSize(
                    512, 512
                )
                .createIntent { intent ->
                    startForProfileImageResult.launch(intent)
                }
        }

        binding.updateBtn.setOnClickListener {
            if (validateData()) {
                updateUserProfile()
            }
        }

        getUserProfile()

        return binding.root
    }

    private fun getUserProfile() {
//        Utils.showLoading(requireActivity(), false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(requireActivity()))
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getUserProfileData_api)
            .addHeaders(Utils.getRequestHeader(requireActivity()))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getUserProfileData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                UserProfileModel::class.java,
                object : ParsedRequestListener<UserProfileModel> {
                    override fun onResponse(response: UserProfileModel) {
//                        Utils.cancelLoading()
                        if (response.getMessageDecrypted()!!
                                .isEmpty() || response.getMessageDecrypted()!!
                                .contains("success", true)
                        ) {
                            handleProfileData(response.getDataDecrypted()!!)
                        } else {
                            Utils.showMessageDialog(
                                requireActivity(),
                                "User Profile",
                                response.getMessageDecrypted()!!,
                                true
                            ) { p0 -> p0?.dismiss() }
                        }
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        Utils.showMessageDialog(
                            requireActivity(),
                            "User Profile",
                            "Some Error Occurred, Please Try Again Later",
                            true
                        ) { p0 -> p0?.dismiss() }
//                        Utils.cancelLoading()
                    }
                })
    }

    private fun updateUserProfile() {
        Utils.showLoading(requireActivity(), false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(requireActivity()))
            jsonObject.put("username", binding.usernameET.text.toString())
            jsonObject.put("email", binding.emailET.text.toString())
            jsonObject.put("state", binding.stateET.text.toString())
            jsonObject.put("password", binding.passwordET.text.toString())
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        if (profilePic.isNotEmpty()) {
            val profile = File(profilePic)
            if (profile.exists()) {
                val multiPartFileMap: MutableMap<String, File> = HashMap()
                multiPartFileMap["profile"] = profile
                updateWithUserProfile(jsonObject, multiPartFileMap)
            } else {
                updateWithoutUserProfile(jsonObject)
            }
        } else {
            updateWithoutUserProfile(jsonObject)
        }
    }

    private fun updateWithoutUserProfile(
        jsonObject: JSONObject
    ) {
        AndroidNetworking.post(ApiConstants.saveUserProfileData_api)
            .addHeaders(Utils.getRequestFileHeader(requireActivity()))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("saveUserProfileData")
            .setPriority(Priority.MEDIUM)
            .build()
            .setUploadProgressListener() { bytesUploaded, totalBytes ->
                val progressPercent = (bytesUploaded * 100) / totalBytes
                Log.d(Variables.TAG, "onProgress: $progressPercent")
            }
            .getAsObject(
                UserProfileModel::class.java,
                object : ParsedRequestListener<UserProfileModel> {
                    override fun onResponse(response: UserProfileModel) {
                        Utils.cancelLoading()
                        handleUpdateResponse(response)
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        Utils.cancelLoading()
                        Utils.showMessageDialog(
                            requireActivity(),
                            "User Profile",
                            "Some Error Occurred, Please Try Again Later",
                            true
                        ) { p0 -> p0?.dismiss() }
                    }
                })
    }

    private fun updateWithUserProfile(
        jsonObject: JSONObject,
        multiPartFileMap: MutableMap<String, File>
    ) {
        AndroidNetworking.upload(ApiConstants.saveUserProfileData_api)
            .addHeaders(Utils.getRequestFileHeader(requireActivity()))
            .addMultipartFile(multiPartFileMap)
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("saveUserProfileData")
            .setPriority(Priority.MEDIUM)
            .build()
            .setUploadProgressListener() { bytesUploaded, totalBytes ->
                val progressPercent = (bytesUploaded * 100) / totalBytes
                Log.d(Variables.TAG, "onProgress: $progressPercent")
            }
            .getAsObject(
                UserProfileModel::class.java,
                object : ParsedRequestListener<UserProfileModel> {
                    override fun onResponse(response: UserProfileModel) {
                        Utils.cancelLoading()
                        handleUpdateResponse(response)
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        Utils.cancelLoading()
                        Utils.showMessageDialog(
                            requireActivity(),
                            "User Profile",
                            "Some Error Occurred, Please Try Again Later",
                            true
                        ) { p0 -> p0?.dismiss() }
                    }
                })
    }

    private fun handleUpdateResponse(response: UserProfileModel) {
        if (response.getMessageDecrypted()!!
                .isEmpty() || response.getMessageDecrypted()!!
                .contains("success", true)
        ) {
            SharedPrefsManager.setPassword(
                requireActivity(),
                response.getDataDecrypted()!!.password_decrypted
            )
            SharedPrefsManager.setUsername(
                requireActivity(),
                response.getDataDecrypted()!!.username
            )
            SharedPrefsManager.setPhoneNumber(
                requireActivity(),
                response.getDataDecrypted()!!.phone_number
            )
            Toast.makeText(
                requireActivity(),
                "Profile Updated!",
                Toast.LENGTH_SHORT
            ).show()
            handleProfileData(response.getDataDecrypted()!!)
        } else {
            Utils.showMessageDialog(
                requireActivity(),
                "User Profile",
                response.getMessageDecrypted()!!,
                true
            ) { p0 -> p0?.dismiss() }
        }
    }

    private fun handleProfileData(profileData: UserProfileModel.UserProfileData) {
        binding.usernameET.setText("${profileData.username}")
        binding.emailET.setText("${profileData.email}")
        binding.stateET.setText("${profileData.state}")
        binding.numberET.setText("${profileData.phone_number}")
        binding.passwordET.setText("${profileData.password_decrypted}")

        Glide.with(requireActivity()).load(profileData.profile_pic)
            .apply(
                RequestOptions()
                    .centerInside()
                    .diskCacheStrategy(DiskCacheStrategy.RESOURCE)
                    .placeholder(R.drawable.ic_profile_pic)
                    .format(DecodeFormat.PREFER_RGB_565)
            )
            .thumbnail(0.08f)
            .into(binding.profilePicImg)
    }

    private val startForProfileImageResult =
        registerForActivityResult(ActivityResultContracts.StartActivityForResult()) { result: ActivityResult ->
            val resultCode = result.resultCode
            val data = result.data

            when (resultCode) {
                Activity.RESULT_OK -> {
                    val fileUri = data?.data!!
                    val file = Utils.copyFileToCache(requireActivity(), fileUri, ".jpg")
                    profilePic = file.absolutePath
                    binding.profilePicImg.setImageURI(fileUri)
                }
                ImagePicker.RESULT_ERROR -> {
                    Toast.makeText(
                        requireActivity(),
                        ImagePicker.getError(data),
                        Toast.LENGTH_SHORT
                    ).show()
                }
                else -> {
                    Toast.makeText(requireActivity(), "Failed To Pick Image", Toast.LENGTH_SHORT)
                        .show()
                }
            }
        }

    private fun validateData(): Boolean {
        if (TextUtils.isEmpty(binding.usernameET.text)) {
            binding.usernameET.error = "* Required"
            return false
        } else if (TextUtils.isEmpty(binding.emailET.text)) {
            binding.emailET.error = "* Required"
            return false
        } else if (TextUtils.isEmpty(binding.stateET.text)) {
            binding.stateET.error = "* Required"
            return false
        } else if (TextUtils.isEmpty(binding.passwordET.text)) {
            binding.passwordET.error = "* Required"
            return false
        } else if (binding.passwordET.text.toString().length < 6) {
            binding.passwordET.error = "Password Should Be Of Atleast 6 Characters"
            return false
        }
        return true
    }

    companion object {
        fun newInstance(): ProfileFragment {
            return ProfileFragment()
        }
    }
}