package com.devshiv.expertinstitute.activities

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.util.Log
import android.view.MenuItem
import android.view.View
import android.webkit.URLUtil
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import com.devshiv.expertinstitute.R
import com.devshiv.expertinstitute.adapter.HomePagerAdapter
import com.devshiv.expertinstitute.databinding.ActivityMainBinding
import com.devshiv.expertinstitute.fragments.HomeFragment
import com.devshiv.expertinstitute.fragments.LiveVideosFragment
import com.devshiv.expertinstitute.fragments.MyOrdersFragment
import com.devshiv.expertinstitute.fragments.ProfileFragment
import com.devshiv.expertinstitute.utils.SharedPrefsManager
import com.devshiv.expertinstitute.utils.Variables
import com.devshiv.expertinstitute.utils.Variables.TAG
import com.google.android.material.navigation.NavigationView
import com.google.firebase.auth.FirebaseAuth
import nl.joery.animatedbottombar.AnimatedBottomBar

class MainActivity : AppCompatActivity(), NavigationView.OnNavigationItemSelectedListener {

    private lateinit var binding: ActivityMainBinding
    var fragmentsList: java.util.ArrayList<Fragment>? = null
    var viewPagerAdapter: HomePagerAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.navView.setNavigationItemSelectedListener(this)

        binding.contentMain.menuImg.setOnClickListener(View.OnClickListener {
            if (!binding.drawerLayout.isDrawerOpen(binding.navView)) {
                binding.drawerLayout.openDrawer(binding.navView)
            } else {
                binding.drawerLayout.closeDrawer(binding.navView)
            }
        })

        binding.contentMain.notificationsImg.setOnClickListener(View.OnClickListener {
            val homeIntent = Intent(this@MainActivity, NotificationsActivity::class.java)
            startActivity(homeIntent)
        })

        setupViewPager()
    }

    private fun setupViewPager() {
        fragmentsList = ArrayList()
        fragmentsList!!.add(HomeFragment.newInstance())
        fragmentsList!!.add(MyOrdersFragment.newInstance())
        fragmentsList!!.add(LiveVideosFragment.newInstance())
        fragmentsList!!.add(ProfileFragment.newInstance())

        viewPagerAdapter = HomePagerAdapter(supportFragmentManager, lifecycle, fragmentsList!!)
        binding.contentMain.viewPager.offscreenPageLimit = fragmentsList!!.size
        binding.contentMain.viewPager.isUserInputEnabled = false
        binding.contentMain.viewPager.adapter = viewPagerAdapter

        binding.contentMain.bottomBar.selectTabAt(0, false)
        binding.contentMain.bottomBar.setOnTabSelectListener(object :
            AnimatedBottomBar.OnTabSelectListener {
            override fun onTabSelected(
                i: Int,
                tab: AnimatedBottomBar.Tab?,
                curTab: Int,
                tab1: AnimatedBottomBar.Tab
            ) {
                binding.contentMain.viewPager.currentItem = curTab
            }

            override fun onTabReselected(i: Int, tab: AnimatedBottomBar.Tab) {
                Log.d(TAG, "onTabReselected: $i")
            }
        })
    }

    override fun onNavigationItemSelected(item: MenuItem): Boolean {
        if (!isFinishing) {
            when (item.itemId) {
                R.id.nav_home -> {
                    val homeIntent = Intent(this@MainActivity, MainActivity::class.java)
                    startActivity(homeIntent)
                    finish()
                }

                R.id.nav_rate -> {
                    val intent = Intent(Intent.ACTION_VIEW)
                    intent.data =
                        Uri.parse(Variables.settingsModel.more_apps_url)
                    startActivity(intent)
                }

                R.id.nav_privacy -> if (URLUtil.isValidUrl(Variables.settingsModel.privacy_policy)) {
                    val privacyIntent = Intent(Intent.ACTION_VIEW)
                    privacyIntent.data = Uri.parse(Variables.settingsModel.privacy_policy)
                    startActivity(privacyIntent)
                }

                R.id.nav_share -> {
                    val sharingIntent = Intent(Intent.ACTION_SEND)
                    sharingIntent.type = "text/plain"
                    sharingIntent.putExtra(Intent.EXTRA_SUBJECT, getString(R.string.app_name))
                    sharingIntent.putExtra(
                        Intent.EXTRA_TEXT,
                        Variables.settingsModel.share_message + " App Link : " + Variables.settingsModel.app_download_link
                    )
                    startActivity(Intent.createChooser(sharingIntent, "Share Via"))
                }

                R.id.nav_logout -> {
                    FirebaseAuth.getInstance().signOut()
                    SharedPrefsManager.setPhoneNumber(this@MainActivity, "")
                    SharedPrefsManager.setLoginStatus(this@MainActivity, false)
                    startActivity(Intent(this@MainActivity, LoginActivity::class.java))
                    finish()
                }

                R.id.nav_exit -> finish()
            }
        }
        return false
    }

    override fun onBackPressed() {
        super.onBackPressed()
        if (binding.drawerLayout.isDrawerOpen(binding.navView)) {
            binding.drawerLayout.closeDrawer(binding.navView)
        } else {
            finish()
        }
    }

}