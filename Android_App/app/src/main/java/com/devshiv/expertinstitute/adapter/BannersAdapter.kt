package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DecodeFormat
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.request.RequestOptions
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.adapter.BannersAdapter.BannersViewHolder
import com.devshiv.expertinstitute.databinding.ItemSliderImageBinding
import com.devshiv.expertinstitute.model.BannerModel.BannerData
import com.devshiv.expertinstitute.utils.MyOnClickListener
import com.smarteist.autoimageslider.SliderViewAdapter

class BannersAdapter(
    var context: Context,
    var mSliderItems: ArrayList<BannerData>,
    var bannerClick: MyOnClickListener
) : SliderViewAdapter<BannersViewHolder>() {

    override fun onCreateViewHolder(parent: ViewGroup): BannersViewHolder {
        val binding = ItemSliderImageBinding.inflate(
            LayoutInflater.from(
                context
            ), parent, false
        )
        return BannersViewHolder(binding)
    }

    override fun onBindViewHolder(holder: BannersViewHolder, position: Int) {
        Glide.with(context).load(mSliderItems[position].image)
            .apply(
                RequestOptions()
                    .centerInside()
                    .diskCacheStrategy(DiskCacheStrategy.RESOURCE)
                    .placeholder(R.drawable.btn_gradient_2)
                    .format(DecodeFormat.PREFER_RGB_565)
            )
            .thumbnail(0.08f)
            .into(holder.bannerView.bannerImageView)
        if (mSliderItems[position].url != null && mSliderItems.get(position).url!!.trim { it <= ' ' } != "") {
            holder.bannerView.bannerImageView.setOnClickListener(View.OnClickListener {
                bannerClick.onClick(
                    position
                )
            })
        }
    }

    override fun getCount(): Int {
        return mSliderItems.size
    }

    class BannersViewHolder(var bannerView: ItemSliderImageBinding) : ViewHolder(
        bannerView.root
    )
}