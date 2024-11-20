package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.devshiv.expertinstitute.databinding.LayoutMockTestBinding
import com.devshiv.expertinstitute.model.MockTestModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class MockTestAdapter(
    var context: Context?,
    var dataList: ArrayList<MockTestModel.MockTestData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<MockTestAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutMockTestBinding =
            LayoutMockTestBinding.inflate(LayoutInflater.from(context), parent, false)
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

    class HomeItemViewHolder(itemView: LayoutMockTestBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutMockTestBinding

        init {
            binding = itemView
        }
    }

}