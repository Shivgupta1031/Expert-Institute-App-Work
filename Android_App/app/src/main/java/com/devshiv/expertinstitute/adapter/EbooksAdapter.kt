package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DecodeFormat
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.request.RequestOptions
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.LayoutEbooksBinding
import com.devshiv.expertinstitute.model.EbooksModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class EbooksAdapter(
    var context: Context?,
    var dataList: ArrayList<EbooksModel.EbookData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<EbooksAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutEbooksBinding =
            LayoutEbooksBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.titleTxt.text = dataList!![position].title
        holder.binding.priceTxt.text = "${dataList!![position].price} INR"

        Glide.with(context!!).load(dataList!![position].image)
            .apply(
                RequestOptions()
                    .centerInside()
                    .diskCacheStrategy(DiskCacheStrategy.RESOURCE)
                    .placeholder(R.drawable.btn_gradient_2)
                    .format(DecodeFormat.PREFER_RGB_565)
            )
            .thumbnail(0.08f)
            .into(holder.binding.image)

        if (dataList!![position].is_purchased) {
            holder.binding.buyNowBtn.text = "Open"
            holder.binding.priceContainer.visibility = View.GONE
        } else {
            holder.binding.buyNowBtn.text = "Buy Now"
            holder.binding.priceContainer.visibility = View.VISIBLE
        }

        holder.binding.buyNowBtn.setOnClickListener {
            if (callbacks != null) {
                callbacks!!.onClick(position)
            }
        }
    }

    class HomeItemViewHolder(itemView: LayoutEbooksBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutEbooksBinding

        init {
            binding = itemView
        }
    }

}