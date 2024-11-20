package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DecodeFormat
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.request.RequestOptions
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.LayoutVideoItemBinding
import com.devshiv.expertinstitute.model.YoutubeVideosModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class RecordedVideosAdapter(
    var context: Context?,
    var dataList: ArrayList<YoutubeVideosModel.YoutubeVideosData>?,
    var callbacks: MyOnClickListener?
) : RecyclerView.Adapter<RecordedVideosAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutVideoItemBinding =
            LayoutVideoItemBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.titleTxt.text = dataList!![position].title

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

        holder.binding.playVideoBtn.setOnClickListener {
            if (callbacks != null) {
                callbacks!!.onClick(position)
            }
        }
    }

    class HomeItemViewHolder(itemView: LayoutVideoItemBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutVideoItemBinding

        init {
            binding = itemView
        }
    }

}