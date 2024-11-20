package com.devshiv.expertinstitute.activities

import android.annotation.SuppressLint
import android.content.Intent
import android.graphics.Bitmap
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.view.View
import android.webkit.WebChromeClient
import android.webkit.WebResourceError
import android.webkit.WebResourceRequest
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.appcompat.app.AppCompatActivity
import com.devshiv.expertinstitute.databinding.ActivityWebViewBinding
import com.devshiv.expertinstitute.utils.Variables

class PhonePeWVActivity : AppCompatActivity() {

    var binding: ActivityWebViewBinding? = null
    var url: String? = null
    var success = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityWebViewBinding.inflate(layoutInflater)
        setContentView(binding!!.root)
        setupWebView()
    }

    @SuppressLint("SetJavaScriptEnabled")
    private fun setupWebView() {
        url = intent.getStringExtra("url")
        val webView = binding!!.webView
        val webSettings = webView.settings
        webSettings.javaScriptEnabled = true
        webSettings.domStorageEnabled = true
        webView.webChromeClient = object : WebChromeClient() {
            override fun onProgressChanged(view: WebView, newProgress: Int) {
                super.onProgressChanged(view, newProgress)
                binding!!.progressHorizontal.progress = newProgress
            }
        }
        webView.webViewClient = object : WebViewClient() {
            override fun onReceivedError(
                view: WebView,
                request: WebResourceRequest,
                error: WebResourceError
            ) {
                super.onReceivedError(view, request, error)
                Log.d(Variables.TAG, "WebView error = $error")
                //                showErrorOccurredBottomSheet();
            }

            override fun onPageStarted(view: WebView, url: String, favicon: Bitmap?) {
                super.onPageStarted(view, url, favicon)
                binding!!.progressHorizontal.visibility = View.VISIBLE
            }

            override fun onPageFinished(view: WebView, url: String) {
                super.onPageFinished(view, url)
                binding!!.progressHorizontal.visibility = View.GONE
            }

            override fun shouldOverrideUrlLoading(view: WebView, urlNewString: String): Boolean {
                Log.d(Variables.TAG, "shouldOverrideUrlLoading: $urlNewString")
                if (urlNewString == "https://gkscoop.com/checkpayment") {
                    success = true
                    onBackPressed()
                }
                binding!!.progressHorizontal.visibility = View.VISIBLE
                if (urlNewString.contains("upi://") ||
                    urlNewString.contains("whatsapp://") ||
                    urlNewString.contains("mailto://")
                ) {
                    loadOutsideWebView(urlNewString)
                } else {
                    view.loadUrl(urlNewString)
                }
                return true
            }
        }
        webView.loadUrl(url!!)
    }

    private fun loadOutsideWebView(url: String) {
        Log.d(Variables.TAG, "loadOutsideWebview: $url")
        binding!!.progressHorizontal.visibility = View.GONE
        val intent = Intent(Intent.ACTION_VIEW)
        intent.setData(Uri.parse(url))
        startActivity(intent)
    }

    override fun onBackPressed() {
        if (success) {
            val intent = Intent()
            intent.putExtra("Status", "SUCCESS")
            intent.putExtra("response", "Payment Successful")
            setResult(RESULT_OK, intent)
            super.onBackPressed()
        } else {
            if (binding!!.webView.canGoBack()) {
                binding!!.webView.goBack()
            } else {
                val intent = Intent()
                intent.putExtra("Status", "CANCELLED")
                intent.putExtra("response", "Payment Cancelled")
                setResult(RESULT_OK, intent)
                super.onBackPressed()
            }
        }
    }
}