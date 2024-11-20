package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.devshiv.expertinstitute.databinding.LayoutVideoCategoryBinding
import com.devshiv.expertinstitute.model.VideosCategoryModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class VideosCategoryAdapter(
    var context: Context?,
    var dataList: ArrayList<VideosCategoryModel.VideoCategoryData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<VideosCategoryAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutVideoCategoryBinding =
            LayoutVideoCategoryBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.titleTxt.text = dataList!![position].category
        holder.binding.container.setOnClickListener {
            if (callbacks != null) {
                callbacks!!.onClick(position)
            }
        }
    }

    class HomeItemViewHolder(itemView: LayoutVideoCategoryBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutVideoCategoryBinding

        init {
            binding = itemView
        }
    }

}