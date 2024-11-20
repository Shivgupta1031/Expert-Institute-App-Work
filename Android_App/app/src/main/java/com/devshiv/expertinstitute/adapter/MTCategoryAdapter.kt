package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.devshiv.expertinstitute.databinding.LayoutMockTestCategoryBinding
import com.devshiv.expertinstitute.model.MockTestCategoryModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class MTCategoryAdapter(
    var context: Context?,
    var dataList: ArrayList<MockTestCategoryModel.MockTestCategoryData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<MTCategoryAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutMockTestCategoryBinding =
            LayoutMockTestCategoryBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.titleTxt.text = dataList!![position].title
        holder.binding.priceTxt.text = "${dataList!![position].price} INR"

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

    class HomeItemViewHolder(itemView: LayoutMockTestCategoryBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutMockTestCategoryBinding

        init {
            binding = itemView
        }
    }

}