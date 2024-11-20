package com.devshiv.expertinstitute.activities

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Toast
import com.devshiv.expertinstitute.databinding.ActivityCourseOptionsBinding

class CourseOptionsActivity : AppCompatActivity() {

    lateinit var binding: ActivityCourseOptionsBinding
    var course_id: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityCourseOptionsBinding.inflate(layoutInflater)
        setContentView(binding.root)

        course_id = intent.getIntExtra("course_id",0)

        if (course_id == 0) {
            Toast.makeText(this, "Failed To Open Course", Toast.LENGTH_SHORT).show()
            finish()
        }

        binding.backBtn.setOnClickListener { onBackPressed() }

        binding.liveVideosLayout.setOnClickListener {
            val intent = Intent(this@CourseOptionsActivity, LiveVideosActivity::class.java)
            intent.putExtra("course_id", course_id)
            startActivity(intent)
        }

        binding.recVideosLayout.setOnClickListener {
            val intent = Intent(this@CourseOptionsActivity, VideosCategoryActivity::class.java)
            intent.putExtra("course_id", course_id)
            startActivity(intent)
        }

        binding.mockTestsLayout.setOnClickListener {
            val intent = Intent(this@CourseOptionsActivity, MockTestActivity::class.java)
            intent.putExtra("type", 1)
            intent.putExtra("course_id", course_id)
            intent.putExtra("test_category_id", 0)
            startActivity(intent)
        }

        binding.pdfNotesLayout.setOnClickListener {
            val intent = Intent(this@CourseOptionsActivity, PdfNotesActivity::class.java)
            intent.putExtra("course_id", course_id)
            startActivity(intent)
        }
    }

    override fun onBackPressed() {
        super.onBackPressed()
        finish()
    }
}