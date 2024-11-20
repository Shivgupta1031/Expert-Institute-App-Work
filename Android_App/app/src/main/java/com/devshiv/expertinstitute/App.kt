package com.devshiv.expertinstitute

import android.app.Activity
import android.app.Application
import android.graphics.BitmapFactory
import android.os.Bundle
import android.util.Log
import android.view.WindowManager
import com.androidnetworking.AndroidNetworking
import com.androidnetworking.gsonparserfactory.GsonParserFactory
import com.devshiv.expertinstitute.utils.SharedPrefsManager
import com.devshiv.expertinstitute.utils.Variables.TAG
import com.google.firebase.FirebaseApp
import com.google.firebase.analytics.FirebaseAnalytics
import com.google.firebase.messaging.FirebaseMessaging
import com.google.gson.Gson
import com.google.gson.GsonBuilder
import com.google.gson.TypeAdapter
import com.google.gson.stream.JsonToken
import com.instacart.library.truetime.TrueTime
import java.io.IOException

class App : Application() {

    var thread: Thread? = null
    var runnable: Runnable? = null

    override fun onCreate() {
        super.onCreate()
        FirebaseApp.initializeApp(this)

        runnable = Runnable {
            try {
                TrueTime.build().initialize()
            } catch (e: IOException) {
                Log.d(TAG, "onCreate: " + e.message)
            }
        }
        thread = Thread(runnable)
        thread?.start()

        if (SharedPrefsManager.getLoginStatus(this)) {
            FirebaseAnalytics.getInstance(this).setUserId(SharedPrefsManager.getUid(this))
        }
        val booleanAsIntAdapter: TypeAdapter<Boolean?> = object : TypeAdapter<Boolean?>() {
            @Throws(IOException::class)
            override fun write(out: com.google.gson.stream.JsonWriter?, value: Boolean?) {
                if (value == null) {
                    out?.nullValue()
                } else {
                    out?.value(value)
                }
            }

            @Throws(IOException::class)
            override fun read(`in`: com.google.gson.stream.JsonReader?): Boolean? {
                val peek: com.google.gson.stream.JsonToken? = `in`?.peek()
                return if (peek === JsonToken.BOOLEAN) {
                    `in`?.nextBoolean()
                } else if (peek === JsonToken.NULL) {
                    `in`?.nextNull()
                    null
                } else if (peek === JsonToken.NUMBER) {
                    `in`?.nextInt() !== 0
                } else if (peek === JsonToken.STRING) {
                    `in`?.nextString().equals("1")
                } else {
                    throw IllegalStateException("Expected BOOLEAN or NUMBER but was $peek")
                }
            }
        }

        val gson: Gson = GsonBuilder()
            .registerTypeAdapter(Boolean::class.java, booleanAsIntAdapter)
            .create()

        AndroidNetworking.setParserFactory(GsonParserFactory(gson))
        val options = BitmapFactory.Options()
        options.inPurgeable = true
        options.inPreferQualityOverSpeed = true
        AndroidNetworking.setBitmapDecodeOptions(options)
        AndroidNetworking.enableLogging()
        AndroidNetworking.initialize(applicationContext)
        AndroidNetworking.setConnectionQualityChangeListener { currentConnectionQuality, currentBandwidth ->
            Log.d(
                TAG,
                "onChange: currentConnectionQuality : \$currentConnectionQuality currentBandwidth : \$currentBandwidth"
            )
        }

        FirebaseMessaging.getInstance().subscribeToTopic("all").addOnCompleteListener { task ->
            if (task.isSuccessful) {
                Log.d(TAG, "onComplete: Subscribed To All")
            } else {
                Log.d(TAG, "onComplete: Failed Subscribe")
            }
        }

        setupActivityListener()
    }

    private fun setupActivityListener() {
        registerActivityLifecycleCallbacks(object : ActivityLifecycleCallbacks {
            override fun onActivityCreated(activity: Activity, savedInstanceState: Bundle?) {
                activity.window.setFlags(
                    WindowManager.LayoutParams.FLAG_SECURE,
                    WindowManager.LayoutParams.FLAG_SECURE
                )
            }

            override fun onActivityStarted(activity: Activity) {}
            override fun onActivityResumed(activity: Activity) {}
            override fun onActivityPaused(activity: Activity) {}
            override fun onActivityStopped(activity: Activity) {}
            override fun onActivitySaveInstanceState(activity: Activity, outState: Bundle) {}
            override fun onActivityDestroyed(activity: Activity) {}
        })
    }
}