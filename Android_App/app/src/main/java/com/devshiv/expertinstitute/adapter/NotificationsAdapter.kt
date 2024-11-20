package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.graphics.drawable.Drawable
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.webkit.URLUtil
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DataSource
import com.bumptech.glide.load.DecodeFormat
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.load.engine.GlideException
import com.bumptech.glide.request.RequestListener
import com.bumptech.glide.request.RequestOptions
import com.bumptech.glide.request.target.Target
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.LayoutNotificationBinding
import com.devshiv.expertinstitute.model.NotificationsModel
import com.devshiv.expertinstitute.utils.MyOnClickListener
import com.devshiv.expertinstitute.utils.Variables

class NotificationsAdapter(
    var context: Context?,
    var dataList: ArrayList<NotificationsModel.NotificationsData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<NotificationsAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutNotificationBinding =
            LayoutNotificationBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.notificationTxt.text = dataList!![position].message
        holder.binding.noticeDateTimeTxt.text = dataList!![position].created

        if (!dataList!![position].image.isNullOrEmpty() && dataList!![position].image!!.contains("http")) {
            holder.binding.noticeImg.visibility = View.VISIBLE
            Glide.with(context!!).load(dataList!![position].image)
                .apply(
                    RequestOptions()
                        .centerInside()
                        .placeholder(R.drawable.btn_gradient_2)
                        .diskCacheStrategy(DiskCacheStrategy.RESOURCE)
                        .format(DecodeFormat.PREFER_RGB_565)
                )
                .thumbnail(0.08f)
                .listener(object : RequestListener<Drawable> {
                    override fun onLoadFailed(
                        e: GlideException?,
                        model: Any?,
                        target: Target<Drawable>,
                        isFirstResource: Boolean
                    ): Boolean {
                        Log.d(Variables.TAG, "onError: " + e?.message)
                        holder.binding.noticeImg.visibility = View.GONE
                        return false
                    }

                    override fun onResourceReady(
                        resource: Drawable,
                        model: Any,
                        target: Target<Drawable>?,
                        dataSource: DataSource,
                        isFirstResource: Boolean
                    ): Boolean {
                        return false
                    }

                }).into(holder.binding.noticeImg)
        } else {
            holder.binding.noticeImg.visibility = View.GONE
        }

        if (URLUtil.isValidUrl(dataList!![position].click_url)) {
            holder.binding.mainContainer.setOnClickListener {
                if (callbacks != null) {
                    callbacks!!.onClick(position)
                }
            }
        }
    }

    class HomeItemViewHolder(itemView: LayoutNotificationBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutNotificationBinding

        init {
            binding = itemView
        }
    }

}