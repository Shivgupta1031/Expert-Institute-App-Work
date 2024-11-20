package com.devshiv.expertinstitute.utils

import android.annotation.SuppressLint
import android.app.Notification
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.graphics.Bitmap
import android.graphics.drawable.Drawable
import android.media.RingtoneManager
import android.net.Uri
import android.os.Build
import android.os.Handler
import android.os.Looper
import android.util.Log
import android.webkit.URLUtil
import androidx.core.app.NotificationCompat
import com.bumptech.glide.Glide
import com.bumptech.glide.request.target.CustomTarget
import com.bumptech.glide.request.transition.Transition
import com.devshiv.expertinstitute.R
import com.google.firebase.messaging.FirebaseMessaging
import com.google.firebase.messaging.FirebaseMessagingService
import com.google.firebase.messaging.RemoteMessage

class MyFirebaseMessagingService : FirebaseMessagingService() {

    private var TITLE: String? = null
    private var IMAGE: String? = null
    private var MESSAGE: String? = null
    private var URL: String? = null

    override fun onCreate() {
        super.onCreate()
        FirebaseMessaging.getInstance().subscribeToTopic("all")
    }

    override fun onMessageReceived(remoteMessage: RemoteMessage) {
        super.onMessageReceived(remoteMessage)
        Log.d(TAG, "onMessageReceived: Data Payload = " + remoteMessage.getData().size)
        Log.d(TAG, "onMessageReceived: Notification Payload = " + remoteMessage.getNotification())
        setupNotice(remoteMessage)
    }

    private fun setupNotice(remoteMessage: RemoteMessage) {
        val data: Map<String, String> = remoteMessage.getData()
        if (remoteMessage.getData().size > 2) {
            TITLE = data[Variables.NOTICE_TITLE]
            MESSAGE = data[Variables.NOTICE_MESSAGE]
            IMAGE = data[Variables.NOTICE_IMAGE]
        } else if (remoteMessage.getNotification() != null) {
            TITLE = remoteMessage.getNotification()!!.getTitle()
            MESSAGE = remoteMessage.getNotification()!!.getBody()
            IMAGE = remoteMessage.getNotification()!!.getImageUrl().toString()
        }
        URL = data[Variables.NOTICE_URL]
        Log.d(TAG, "setupNotice: URL = $URL")
        if (URL == null) {
            URL = ""
        }
        if (IMAGE == null) {
            IMAGE = ""
        }
        val uiHandler: Handler = Handler(Looper.getMainLooper())
        uiHandler.post {
            if (!IMAGE!!.isEmpty() && URLUtil.isValidUrl(IMAGE)) {
                Glide.with(this@MyFirebaseMessagingService).asBitmap().load(IMAGE)
                    .into(object : CustomTarget<Bitmap?>() {
                        override fun onResourceReady(
                            resource: Bitmap,
                            transition: Transition<in Bitmap?>?
                        ) {
                            Log.d(TAG, "onResourceReady: ")
                            sendNotification(resource)
                        }

                        override fun onLoadCleared(placeholder: Drawable?) {
                            Log.d(TAG, "onLoadCleared: ")
                        }
                    })
            } else {
                sendNotification(null)
            }
        }
    }

    private fun sendNotification(bitmap: Bitmap?) {
        val defaultSound: Uri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION)
        val notificationManager: NotificationManager =
            getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
        val NOTIFICATION_CHANNEL_ID = "101"
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            @SuppressLint("WrongConstant") val notificationChannel = NotificationChannel(
                NOTIFICATION_CHANNEL_ID,
                "Notification",
                NotificationManager.IMPORTANCE_MAX
            )

            //Configure Notification Channel
            notificationChannel.setDescription("Notifications")
            notificationChannel.enableLights(true)
            notificationChannel.setVibrationPattern(longArrayOf(0, 1000, 500, 1000))
            notificationChannel.enableVibration(true)
            notificationManager.createNotificationChannel(notificationChannel)
        }
        val notificationBuilder: NotificationCompat.Builder =
            NotificationCompat.Builder(this, NOTIFICATION_CHANNEL_ID)
                .setSmallIcon(R.drawable.app_icon)
                .setContentTitle(TITLE)
                .setContentText(MESSAGE)
                .setStyle(NotificationCompat.BigTextStyle().bigText(TITLE))
                .setStyle(NotificationCompat.BigTextStyle().bigText(MESSAGE))
                .setAutoCancel(true)
                .setSound(defaultSound)
                .setLargeIcon(bitmap)
                .setWhen(System.currentTimeMillis())
                .setPriority(Notification.PRIORITY_MAX)
        if (!URL!!.isEmpty() && URLUtil.isValidUrl(URL)) {
            val intent = Intent(Intent.ACTION_VIEW)
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_SINGLE_TOP)
            intent.setData(Uri.parse(URL))
            val pendingIntent: PendingIntent = PendingIntent.getActivity(this, 0, intent, PendingIntent.FLAG_IMMUTABLE)
            notificationBuilder.setContentIntent(pendingIntent)
        }
        if (bitmap != null) {
            val style: NotificationCompat.BigPictureStyle = NotificationCompat.BigPictureStyle()
            style.bigPicture(bitmap)
            notificationBuilder.setStyle(style)
        }
        notificationManager.notify(1, notificationBuilder.build())
    }

    companion object {
        private const val TAG = "MyFirebaseMessagingServ"
    }
}
