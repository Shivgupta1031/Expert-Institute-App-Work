package com.devshiv.expertinstitute.utils

import android.os.Handler
import android.os.Message
import android.os.SystemClock

abstract class CountDownTimerWithPause(
    private var mMillisInFuture: Long,
    var mCountdownInterval: Long,
    var mRunAtStart: Boolean
) {

    companion object {
        private const val MSG = 1
    }

    private var mStopTimeInFuture: Long = 0
    private val mTotalCountdown: Long = mMillisInFuture
    private var mPauseTimeRemaining: Long = 0

    fun getmPauseTimeRemaining(): Long {
        return mPauseTimeRemaining
    }

    fun setmPauseTimeRemaining(mPauseTimeRemaining: Long) {
        this.mPauseTimeRemaining = mPauseTimeRemaining
    }

    fun cancel() {
        mHandler.removeMessages(MSG)
    }

    @Synchronized
    fun create(): CountDownTimerWithPause {
        if (mMillisInFuture <= 0) {
            onFinish()
        } else {
            mPauseTimeRemaining = mMillisInFuture
        }
        if (mRunAtStart) {
            resume()
        }
        return this
    }

    fun pause() {
        if (isRunning) {
            mPauseTimeRemaining = timeLeft()
            cancel()
        }
    }

    fun resume() {
        if (isPaused) {
            mMillisInFuture = mPauseTimeRemaining
            mStopTimeInFuture = SystemClock.elapsedRealtime() + mMillisInFuture
            mHandler.sendMessage(mHandler.obtainMessage(MSG))
            mPauseTimeRemaining = 0
        }
    }

    val isPaused: Boolean
        get() = mPauseTimeRemaining > 0
    val isRunning: Boolean
        get() = !isPaused

    fun timeLeft(): Long {
        var millisUntilFinished: Long
        if (isPaused) {
            millisUntilFinished = mPauseTimeRemaining
        } else {
            millisUntilFinished = mStopTimeInFuture - SystemClock.elapsedRealtime()
            if (millisUntilFinished < 0) millisUntilFinished = 0
        }
        return millisUntilFinished
    }

    fun totalCountdown(): Long {
        return mTotalCountdown
    }

    fun timePassed(): Long {
        return mTotalCountdown - timeLeft()
    }

    fun hasBeenStarted(): Boolean {
        return mPauseTimeRemaining < mMillisInFuture
    }

    abstract fun onTick(millisUntilFinished: Long)
    abstract fun onFinish()

    // handles counting down
    private val mHandler: Handler = object : Handler() {
        override fun handleMessage(msg: Message) {
            synchronized(this@CountDownTimerWithPause) {
                val millisLeft = timeLeft()
                if (millisLeft <= 0) {
                    cancel()
                    onFinish()
                } else if (millisLeft < mCountdownInterval) {
                    // no tick, just delay until done
                    sendMessageDelayed(obtainMessage(MSG), millisLeft)
                } else {
                    val lastTickStart = SystemClock.elapsedRealtime()
                    onTick(millisLeft)

                    // take into account user's onTick taking time to execute
                    var delay = mCountdownInterval - (SystemClock.elapsedRealtime() - lastTickStart)

                    // special case: user's onTick took more than mCountdownInterval to
                    // complete, skip to next interval
                    while (delay < 0) delay += mCountdownInterval
                    sendMessageDelayed(obtainMessage(MSG), delay)
                }
            }
        }
    }

}