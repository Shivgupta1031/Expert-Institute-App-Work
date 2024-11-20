package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.devshiv.expertinstitute.databinding.LayoutSyllabusBinding
import com.devshiv.expertinstitute.model.SyllabusModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class SyllabusAdapter(
    var context: Context?,
    var dataList: ArrayList<SyllabusModel.SyllabusData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<SyllabusAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutSyllabusBinding =
            LayoutSyllabusBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.titleTxt.text = dataList!![position].title
        holder.binding.container.setOnClickListener {
            if (callbacks != null) {
                callbacks!!.onClick(position)
            }
        }
    }

    class HomeItemViewHolder(itemView: LayoutSyllabusBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutSyllabusBinding

        init {
            binding = itemView
        }
    }

}