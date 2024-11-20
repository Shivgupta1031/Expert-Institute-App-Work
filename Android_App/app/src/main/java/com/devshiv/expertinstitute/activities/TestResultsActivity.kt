package com.devshiv.expertinstitute.activities

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.widget.Toast
import com.devshiv.expertinstitute.databinding.ActivityTestResultsBinding
import com.devshiv.expertinstitute.model.TestQuestionsModel
import com.devshiv.expertinstitute.utils.Utils
import com.devshiv.expertinstitute.utils.Variables.TAG
import java.text.DecimalFormat
import java.text.NumberFormat

class TestResultsActivity : AppCompatActivity() {

    lateinit var binding: ActivityTestResultsBinding
    var dataList: ArrayList<TestQuestionsModel.TestQuestionsData>? = null
    var time_taken: Long = 0
    var mock_test_id: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityTestResultsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        dataList =
            intent.getSerializableExtra("data") as ArrayList<TestQuestionsModel.TestQuestionsData>
        time_taken = intent.getLongExtra("time_taken", 0)
        mock_test_id = intent.getIntExtra("mock_test_id", 0)

        Log.d(TAG, "onCreate: $time_taken")

        if (dataList == null) {
            Toast.makeText(this, "Failed To Show Results", Toast.LENGTH_SHORT).show()
            finish()
        }

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }

        binding.viewAnswersBtn.setOnClickListener {
            val intent = Intent(this@TestResultsActivity, QuestionsActivity::class.java)
            intent.putExtra("mock_test_id", mock_test_id)
            intent.putExtra("test_time", 0)
            intent.putExtra("showAnswers", true)
            intent.putExtra("data", dataList)
            startActivity(intent)
        }

        setupResults()
    }

    private fun setupResults() {

        Utils.showLoading(this, false)

        var totalQuestions = dataList!!.size
        var totalQuesAnswered = 0
        var totalCorrectAnswers = 0
        var totalWrongAnswers = 0
        var totalTimeTaken = ""

        for (data: TestQuestionsModel.TestQuestionsData in dataList!!) {
            if (data.user_answer != 0) {
                totalQuesAnswered += 1
                if (data.user_answer == data.correct_option_no) {
                    totalCorrectAnswers += 1
                } else {
                    totalWrongAnswers += 1
                }
            }
        }

        val f: NumberFormat = DecimalFormat("00")
        val hour = time_taken / 3600000 % 24
        val min = time_taken / 60000 % 60
        val sec = time_taken / 1000 % 60
        totalTimeTaken = f.format(hour) + ":" + f.format(min) + ":" + f.format(sec)

        binding.totalTimeTxt.text = "$totalTimeTaken"
        binding.totalQuestionsTxt.text = "$totalQuestions"
        binding.questAnsweredTxt.text = "$totalQuesAnswered"
        binding.correctAnswersTxt.text = "$totalCorrectAnswers"
        binding.wrongAnswersTxt.text = "$totalWrongAnswers"

        Utils.cancelLoading()
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }
}