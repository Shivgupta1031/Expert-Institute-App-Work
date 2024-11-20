package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.devshiv.expertinstitute.databinding.LayoutPaidCourseBinding
import com.devshiv.expertinstitute.model.PaidCourseData
import com.devshiv.expertinstitute.utils.MyOnClickListener

class PaidCourseAdapter(
    var context: Context?,
    var dataList: ArrayList<PaidCourseData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<PaidCourseAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutPaidCourseBinding =
            LayoutPaidCourseBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.homeView.titleTxt.text = dataList!![position].title
        holder.homeView.priceTxt.text = "${dataList!![position].price} INR"

        holder.homeView.courseContainer.setOnClickListener {
            if (callbacks!=null){
                callbacks!!.onClick(position)
            }
        }
    }

    class HomeItemViewHolder(itemView: LayoutPaidCourseBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var homeView: LayoutPaidCourseBinding

        init {
            homeView = itemView
        }
    }

}