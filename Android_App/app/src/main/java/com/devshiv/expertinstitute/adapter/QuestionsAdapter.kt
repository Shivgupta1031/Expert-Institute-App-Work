package com.devshiv.expertinstitute.adapter

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.databinding.LayoutQuestionsPagerBinding
import com.devshiv.expertinstitute.model.TestQuestionsModel
import com.devshiv.expertinstitute.utils.MyOnClickListener

class QuestionsAdapter(
    var context: Context?,
    var dataList: ArrayList<TestQuestionsModel.TestQuestionsData>?,
    var callbacks: MyOnClickListener?,
    var showAnswers: Boolean = false
) : RecyclerView.Adapter<QuestionsAdapter.HomeItemViewHolder?>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HomeItemViewHolder {
        val binding: LayoutQuestionsPagerBinding =
            LayoutQuestionsPagerBinding.inflate(LayoutInflater.from(context), parent, false)
        return HomeItemViewHolder(binding)
    }

    override fun getItemCount(): Int {
        return dataList!!.size
    }

    override fun onBindViewHolder(holder: HomeItemViewHolder, position: Int) {
        holder.binding.questionTxt.text = dataList!![position].question
        holder.binding.optATxt.text = dataList!![position].opt_1
        holder.binding.optBTxt.text = dataList!![position].opt_2
        holder.binding.optCTxt.text = dataList!![position].opt_3
        holder.binding.optDTxt.text = dataList!![position].opt_4

        when (dataList!![position].user_answer) {
            1 -> {
                setSelectedOption(holder.binding, holder.binding.optACont)
            }
            2 -> {
                setSelectedOption(holder.binding, holder.binding.optBCont)
            }
            3 -> {
                setSelectedOption(holder.binding, holder.binding.optCCont)
            }
            4 -> {
                setSelectedOption(holder.binding, holder.binding.optDCont)
            }
        }

        if (!showAnswers) {
            holder.binding.optACont.setOnClickListener {
                val myData = IntArray(2)
                myData[0] = position
                myData[1] = 1
                callbacks?.onClick(myData)
            }

            holder.binding.optBCont.setOnClickListener {
                val myData = IntArray(2)
                myData[0] = position
                myData[1] = 2
                callbacks?.onClick(myData)
            }

            holder.binding.optCCont.setOnClickListener {
                val myData = IntArray(2)
                myData[0] = position
                myData[1] = 3
                callbacks?.onClick(myData)
            }

            holder.binding.optDCont.setOnClickListener {
                val myData = IntArray(2)
                myData[0] = position
                myData[1] = 4
                callbacks?.onClick(myData)
            }
        } else {
            when (dataList!![position].correct_option_no) {
                1 -> {
                    holder.binding.optACont.setBackgroundColor(
                        ContextCompat.getColor(
                            context!!,
                            carbon.R.color.carbon_lightGreen_a200
                        )
                    )
                }
                2 -> {
                    holder.binding.optBCont.setBackgroundColor(
                        ContextCompat.getColor(
                            context!!,
                            carbon.R.color.carbon_lightGreen_a200
                        )
                    )
                }
                3 -> {
                    holder.binding.optCCont.setBackgroundColor(
                        ContextCompat.getColor(
                            context!!,
                            carbon.R.color.carbon_lightGreen_a200
                        )
                    )
                }
                4 -> {
                    holder.binding.optDCont.setBackgroundColor(
                        ContextCompat.getColor(
                            context!!,
                            carbon.R.color.carbon_lightGreen_a200
                        )
                    )
                }
            }
        }
    }

    private fun setSelectedOption(binding: LayoutQuestionsPagerBinding, selectedView: View) {
        binding.optACont.setBackgroundColor(
            ContextCompat.getColor(
                context!!,
                R.color.white
            )
        )
        binding.optBCont.setBackgroundColor(
            ContextCompat.getColor(
                context!!,
                R.color.white
            )
        )
        binding.optCCont.setBackgroundColor(
            ContextCompat.getColor(
                context!!,
                R.color.white
            )
        )
        binding.optDCont.setBackgroundColor(
            ContextCompat.getColor(
                context!!,
                R.color.white
            )
        )
        selectedView.setBackgroundColor(
            ContextCompat.getColor(
                context!!,
                R.color.colorAccentLight
            )
        )
    }

    class HomeItemViewHolder(itemView: LayoutQuestionsPagerBinding) :
        RecyclerView.ViewHolder(itemView.root) {
        var binding: LayoutQuestionsPagerBinding

        init {
            binding = itemView
        }
    }

}