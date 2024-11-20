package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.viewpager2.widget.ViewPager2.OnPageChangeCallback
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.common.Priority
import com.androidnetworking.error.ANError
import com.androidnetworking.interfaces.ParsedRequestListener
import com.devshiv.expertinstitute.adapter.QuestionsAdapter
import com.devshiv.expertinstitute.databinding.ActivityQuestionsBinding
import com.devshiv.expertinstitute.model.TestQuestionsModel
import com.devshiv.expertinstitute.utils.*
import com.devshiv.expertinstitute.utils.Variables.TAG
import org.json.JSONException
import org.json.JSONObject
import java.text.DecimalFormat
import java.text.NumberFormat

class QuestionsActivity : AppCompatActivity(), MyOnClickListener {

    lateinit var binding: ActivityQuestionsBinding
    var dataList: ArrayList<TestQuestionsModel.TestQuestionsData> = ArrayList()
    var viewPagerAdapter: QuestionsAdapter? = null
    var mock_test_id: Int = 0
    var curPage: Int = 0
    var countDownTimer: CountDownTimerWithPause? = null
    var test_time: Int = 30
    var ques_answered: Int = 0
    var isViewResultOpened = false
    var showAnswers = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityQuestionsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        mock_test_id = intent.getIntExtra("mock_test_id", 0)
        test_time = intent.getIntExtra("test_time", 30)
        showAnswers = intent.getBooleanExtra("showAnswers", false)

        if (mock_test_id == 0) {
            Toast.makeText(this, "Unable To Fetch Questions", Toast.LENGTH_SHORT).show()
            finish()
        }

        binding.nextBtn.setOnClickListener {
            if (curPage < dataList.size - 1) {
                binding.viewPager.setCurrentItem(curPage + 1, true)
            }
        }
        binding.previousBtn.setOnClickListener {
            if (curPage > 0) {
                binding.viewPager.setCurrentItem(curPage - 1, true)
            }
        }

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }

        binding.submitBtn.setOnClickListener {
            Utils.showAlertDialog(
                this@QuestionsActivity,
                "Submit Test",
                "Are You Sure You Want To Submit The Test?",
                true,
                object : MyOnClickListener {
                    override fun onClick(data: Any) {
                        viewResults()
                    }
                }
            )
        }

        binding.questionsAnsweredTxt.text =
            "Questions Answered : ${ques_answered}/${dataList.size}"

        if (showAnswers) {
            binding.timerTxt.visibility = View.GONE
            binding.questionsAnsweredTxt.visibility = View.GONE
            dataList =
                intent.getSerializableExtra("data") as ArrayList<TestQuestionsModel.TestQuestionsData>
            setupViewPager()
        } else {
            setupTimer(true)
            getData()
        }

    }

    private fun setupTimer(playOnStart: Boolean) {
        binding.timerTxt.text = "$test_time"
        countDownTimer = object : CountDownTimerWithPause(
            (test_time * 60 * 1000).toLong(),
            1000,
            playOnStart
        ) {
            override fun onTick(millisUntilFinished: Long) {
                val f: NumberFormat = DecimalFormat("00")
                val hour = millisUntilFinished / 3600000 % 24
                val min = millisUntilFinished / 60000 % 60
                val sec = millisUntilFinished / 1000 % 60
                Log.d(TAG, "onTick: $hour:$min:$sec")
                binding.timerTxt.text = f.format(hour) + ":" + f.format(min) + ":" + f.format(sec)
            }

            override fun onFinish() {
                binding.timerTxt.text = "00:00:00"
                Utils.showMessageDialog(
                    this@QuestionsActivity,
                    "Time Up!",
                    "Test Has Ended, Click OK To View Results!", false,
                ) {
                    viewResults()
                }
            }
        }.create()
        Log.d(TAG, "setupTimer: ")
    }

    private fun viewResults() {
        isViewResultOpened = true
        val intent = Intent(this@QuestionsActivity, TestResultsActivity::class.java)
        intent.putExtra("data", dataList)
        intent.putExtra("time_taken", countDownTimer?.timePassed())
        intent.putExtra("mock_test_id", mock_test_id)
        startActivity(intent)
    }

    private fun getData() {
        Utils.showLoading(this, false)

        val jsonObject = JSONObject()
        try {
            jsonObject.put("uid", SharedPrefsManager.getUid(this))
            jsonObject.put("mock_test_id", mock_test_id)
        } catch (e: JSONException) {
            Log.d(Variables.TAG, "getData: " + e.message)
        }

        AndroidNetworking.post(ApiConstants.getMockTestQuestionsData_api)
            .addHeaders(Utils.getRequestHeader(this))
            .addQueryParameter("data", Utils.encrypt(jsonObject.toString()))
            .setTag("getMockTestQuestionsData")
            .setPriority(Priority.MEDIUM)
            .build()
            .getAsObject(
                TestQuestionsModel::class.java,
                object : ParsedRequestListener<TestQuestionsModel> {
                    override fun onResponse(response: TestQuestionsModel) {
                        if (dataList.isNotEmpty()) {
                            dataList.clear()
                        }
                        dataList.addAll(response.getDataDecrypted()!!)
                        if (dataList.size > 0) {
                            setupViewPager()
                        } else {
                            Toast.makeText(
                                this@QuestionsActivity,
                                "Unable To Fetch Questions",
                                Toast.LENGTH_SHORT
                            ).show()
                            finish()
                        }
                        Utils.cancelLoading()
                    }

                    override fun onError(anError: ANError) {
                        Log.d(Variables.TAG, "onError: " + anError.errorBody)
                        Log.d(Variables.TAG, "onError: " + anError.errorDetail)
                        Utils.cancelLoading()
                        Toast.makeText(
                            this@QuestionsActivity,
                            "Unable To Fetch Questions",
                            Toast.LENGTH_SHORT
                        ).show()
                        finish()
                    }
                })
    }

    private fun setupViewPager() {
        viewPagerAdapter = QuestionsAdapter(this, dataList, this, showAnswers)
        binding.viewPager.offscreenPageLimit = dataList.size
        binding.viewPager.isUserInputEnabled = false
        binding.viewPager.adapter = viewPagerAdapter
        binding.viewPager.registerOnPageChangeCallback(object : OnPageChangeCallback() {
            override fun onPageSelected(position: Int) {
                super.onPageSelected(position)
                curPage = position
                if (!showAnswers) {
                    if (curPage == dataList.size - 1) {
                        binding.submitBtn.visibility = View.VISIBLE
                        binding.nextBtn.visibility = View.GONE
                    } else {
                        binding.submitBtn.visibility = View.GONE
                        binding.nextBtn.visibility = View.VISIBLE
                    }
                    binding.questionsAnsweredTxt.text =
                        "Questions Answered : ${ques_answered}/${dataList.size}"
                }
            }
        })
    }

    override fun onClick(dat: Any) {
        val data = dat as IntArray
        if (dataList[data[0]].user_answer == 0) {
            ques_answered += 1
        }
        if (dataList[data[0]].user_answer != data[1]) {
            dataList[data[0]].user_answer = data[1]
            viewPagerAdapter?.notifyItemChanged(data[0])
        }
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }

    override fun onPause() {
        super.onPause()
        if (countDownTimer != null && countDownTimer!!.hasBeenStarted() && countDownTimer!!.isRunning) {
            countDownTimer!!.pause()
        }
    }

    override fun onResume() {
        super.onResume()
        if (countDownTimer != null && countDownTimer!!.hasBeenStarted() && countDownTimer!!.isPaused) {
            countDownTimer!!.resume()
        }
        if (isViewResultOpened) {
            onBackPressed()
        }
    }

    override fun onDestroy() {
        if (countDownTimer != null) {
            countDownTimer!!.cancel()
            countDownTimer = null
        }
        super.onDestroy()
    }
}