package com.devshiv.expertinstitute.activities

import android.content.pm.ActivityInfo
import android.content.res.Configuration
import android.os.Bundle
import android.text.method.LinkMovementMethod
import android.view.View
import android.view.WindowManager
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.devshiv.expertinstitute.databinding.ActivityPlayVideoBinding
import com.devshiv.expertinstitute.model.YoutubeVideosModel
import com.devshiv.expertinstitute.utils.CustomPlayerUiController
import com.pierfrancescosoffritti.androidyoutubeplayer.core.player.YouTubePlayer
import com.pierfrancescosoffritti.androidyoutubeplayer.core.player.listeners.AbstractYouTubePlayerListener
import com.pierfrancescosoffritti.androidyoutubeplayer.core.player.options.IFramePlayerOptions
import net.nightwhistler.htmlspanner.HtmlSpanner
import java.util.regex.Pattern


class PlayVideoActivity : AppCompatActivity(), View.OnClickListener {

    lateinit var binding: ActivityPlayVideoBinding
    var videoData: YoutubeVideosModel.YoutubeVideosData? = null
    var youTubePlayer: YouTubePlayer? = null

    private var isFullscreen = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityPlayVideoBinding.inflate(layoutInflater)
        setContentView(binding.root)

        videoData = intent.getSerializableExtra("data") as YoutubeVideosModel.YoutubeVideosData

        if (videoData == null) {
            Toast.makeText(this, "Unable To Fetch Details", Toast.LENGTH_SHORT).show()
            finish()
        }

        binding.titleTxt.text = videoData!!.title

        binding.descriptionTxt.text = HtmlSpanner().fromHtml(videoData!!.description)
        binding.descriptionTxt.movementMethod = LinkMovementMethod.getInstance()

        binding.youtubePlayerView.enableAutomaticInitialization = false

        val iFramePlayerOptions: IFramePlayerOptions = IFramePlayerOptions.Builder()
            .controls(1)
            .build()

        binding.youtubePlayerView.initialize(object : AbstractYouTubePlayerListener() {
            override fun onReady(youTubePlayer: YouTubePlayer) {

                val defaultPlayerUiController =
                    CustomPlayerUiController(binding.youtubePlayerView, youTubePlayer)
                defaultPlayerUiController.setFullscreenButtonClickListener(this@PlayVideoActivity)
                binding.youtubePlayerView.setCustomPlayerUi(defaultPlayerUiController.rootView)

                this@PlayVideoActivity.youTubePlayer = youTubePlayer
                this@PlayVideoActivity.youTubePlayer!!.loadVideo(
                    getYouTubeId(videoData!!.video_link!!),
                    0f
                )
            }
        }, iFramePlayerOptions)

        lifecycle.addObserver(binding.youtubePlayerView)

        binding.backBtn.setOnClickListener {
            onBackPressed()
        }
    }

    override fun onConfigurationChanged(config: Configuration) {
        super.onConfigurationChanged(config)
        if (config.orientation === Configuration.ORIENTATION_LANDSCAPE) {
            window.addFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN)
        } else {
            window.clearFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN)
        }
    }

    private fun getYouTubeId(youTubeUrl: String): String {
        val pattern = "(?<=youtu.be/|watch\\?v=|/videos/|embed\\/)[^#\\&\\?]*"
        val compiledPattern = Pattern.compile(pattern)
        val matcher = compiledPattern.matcher(youTubeUrl)
        return if (matcher.find()) {
            matcher.group()
        } else {
            "error"
        }
    }

    override fun onClick(p0: View?) {
        if (isFullscreen) {
            exitFullScreen()
        } else {
            isFullscreen = true

            binding.detailsContainer.visibility = View.GONE
            binding.topBar.visibility = View.GONE
            binding.youtubePlayerView.matchParent()

            requestedOrientation = ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE
        }
    }

    private fun exitFullScreen() {
        isFullscreen = false

        binding.detailsContainer.visibility = View.VISIBLE
        binding.topBar.visibility = View.VISIBLE
        binding.youtubePlayerView.wrapContent()

        requestedOrientation = ActivityInfo.SCREEN_ORIENTATION_PORTRAIT
    }

    override fun onBackPressed() {
        super.onBackPressed()
        if (isFullscreen) {
            exitFullScreen()
        } else {
            finish()
        }
    }

}