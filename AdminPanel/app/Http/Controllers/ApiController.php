<?php

namespace App\Http\Controllers;

use App\Models\AddFundsRequest;
use App\Models\AppSetting;
use App\Models\Banners;
use App\Models\Coupans;
use App\Models\Ebooks;
use App\Models\HourlyBonusRecords;
use App\Models\LuckyDrawList;
use App\Models\LuckyDrawParticipants;
use App\Models\ManualPaymentRequests;
use App\Models\MissionRecords;
use App\Models\Missions;
use App\Models\MockTestCategory;
use App\Models\MockTests;
use App\Models\Notifications;
use App\Models\PaidCourses;
use App\Models\PDFNotes;
use App\Models\Setting;
use App\Models\SpinRecords;
use App\Models\Syllabus;
use App\Models\TestQuestions;
use App\Models\UserOrders;
use App\Models\Users;
use App\Models\Videos;
use App\Models\VideosCategory;
use App\Models\WithdrawalOptions;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use GeoIP;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{

    function checkAuthentication(Request $req)
    {
        $jsondata = json_decode($req->getContent(), true);
        if (array_key_exists('authToken', $jsondata)) {
            $key = $jsondata['authToken'];
            if ($key == env('AUTHENTICATION_TOKEN')) {
                return true;
            }
        }
        return false;
    }

    function getSettings(Request $req)
    {
        $data = [];

        if ($this->checkAuthentication($req)) {
            $jsondata = json_decode($req->getContent(), true);
            $data['is_blocked'] = false;
            if (array_key_exists('uid', $jsondata)) {
                $uid = $jsondata['uid'];
                if (Users::where('uid', $uid)->exists()) {
                    $user = Users::where('uid', $uid)->first();
                    $user->last_active = now();
                    if ($user->account_status == 1) {
                        $data['is_blocked'] = true;
                    }
                    $user->save();
                }
            }
            $data['app_settings'] = base64_encode(base64_encode(AppSetting::first()));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Authentication Failed'));
            return response()->json($data, 401);
        }
    }

    function registerUser(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);
        geoip()->getLocation($ip = null);
        $ip = $req->ip();
        $arr_ip = geoip()->getLocation($ip);

        if (array_key_exists('phone_number', $jsondata)) {
            $username = $jsondata['username'];
            $phone_number = $jsondata['phone_number'];
            $password = $jsondata['password'];
            $token = $jsondata['token'];
            $device_id = $jsondata['device_id'];

            if (strlen($phone_number) > 0 && Users::where('phone_number', $phone_number)->exists()) {
                $data['message'] = base64_encode(base64_encode('User With This Phone Number Already Exists'));
                return response()->json($data, 210);
            }
            if (strlen($device_id) > 0 && Users::where('device_id', $device_id)->exists()) {
                $data['message'] = base64_encode(base64_encode('Only 1 Account Is Allowed Per Device'));
                return response()->json($data, 210);
            }

            $uid = Str::upper(Str::random(20));

            $user = Users::create([
                'uid' => $uid,
                'username' => $username,
                'phone_number' => $phone_number,
                'password' => base64_encode(base64_encode($password)),
                'device_id' => $device_id,
                'token' => $token,
                'ip_address' => $ip,
                'country' => $arr_ip->iso_code,
                'last_active' => now(),
                'created' => now(),
            ]);

            $user->tokens()->delete();
            $token = $user->createToken('my-app-token')->plainTextToken;

            $data['data'] = base64_encode(base64_encode($user));
            $data['app_settings'] = base64_encode(base64_encode(AppSetting::first()));
            $data['token'] = base64_encode(base64_encode($token . $user->token));
            $data['message'] = base64_encode(base64_encode('Account Created Successfully'));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function loginUser(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);
        geoip()->getLocation($ip = null);
        $ip = $req->ip();
        $arr_ip = geoip()->getLocation($ip);

        if (array_key_exists('phone_number', $jsondata) && array_key_exists('password', $jsondata)) {

            $phone_number = $jsondata['phone_number'];
            $password = $jsondata['password'];
            if (array_key_exists('isForgetPass', $jsondata)) {
                $isForgetPass = $jsondata['isForgetPass'];
            } else {
                $isForgetPass = 0;
            }

            if (!Users::where('phone_number', $phone_number)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found With This Phone Number'));
                return response()->json($data, 210);
            }

            $user = Users::where('phone_number', $phone_number)->first();

            if ($isForgetPass == 0) {
                if (base64_decode(base64_decode($user->password)) != $password) {
                    $data['message'] = base64_encode(base64_encode('Incorrect Password'));
                    return response()->json($data, 210);
                }
            }

            if ($user->account_status == 1) {
                $data['message'] = base64_encode(base64_encode('This Account Has Been Disabled'));
                return response()->json($data, 210);
            }

            $user->ip_address = $ip;
            $user->country = $arr_ip->iso_code;

            $user->last_active = now();
            if ($user->account_status == 1) {
                $data['is_blocked'] = true;
            } else {
                $data['is_blocked'] = false;
            }
            $user->save();

            $user->tokens()->delete();
            $token = $user->createToken('my-app-token')->plainTextToken;

            $data['data'] = base64_encode(base64_encode($user));
            $data['app_settings'] = base64_encode(base64_encode(AppSetting::first()));
            $data['token'] = base64_encode(base64_encode($token . $user->token));
            $data['message'] = base64_encode(base64_encode('Login Successful'));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function forgotPass(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('username', $jsondata)) {
            $username = $jsondata['username'];

            if (!Users::where('username', $username)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found With This Username'));
                return response()->json($data, 210);
            }

            $user = Users::where('username', $username)->first();

            if (strlen($user->email) == 0) {
                $data['message'] = base64_encode(base64_encode('No Email Address Found For This Username'));
                return response()->json($data, 210);
            }

            $newPass = Str::random(6);

            $mail_date = [
                'recipient' => $user->email,
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('APP_NAME'),
                'subject' => 'Forgot Password',
                'body' => 'New Password For Your Account Is : ' . $newPass
            ];

            Mail::send('email-template', $mail_date, function ($message) use ($mail_date) {
                $message->to($mail_date['recipient'])
                    ->from($mail_date['fromEmail'], $mail_date['fromName'])
                    ->subject($mail_date['subject']);
            });

            $user->password = Hash::make($newPass);
            $user->save();

            $data['message'] = base64_encode(base64_encode('New Password Sent Successfully'));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getBannersData(Request $req)
    {
        $data = [];
        $data['data'] = base64_encode(base64_encode(Banners::orderByDesc('id')->get()));
        return response()->json($data, 200);
    }

    function getPaidCoursesData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $model = new PaidCourses();
            $model::$uid = $uid;
            $appData = $model::where('is_active', 0)->orderByDesc('id')->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getVideosCategoryData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $course_id = $jsondata['course_id'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if ($course_id == 0) {
                $appData = VideosCategory::orderByDesc('id')->get();
            } else {
                $ids = Videos::where('course_id', $course_id)->where('video_type', 0)->select('category_id')->pluck('category_id');
                $appData = VideosCategory::whereIn('id', $ids)->orderByDesc('id')->get();
            }

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getVideosData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $course_id = $jsondata['course_id'];
            $video_type = $jsondata['video_type'];
            $category_id = $jsondata['category_id'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $mainData = [];
            if ($video_type == 1) {
                $mainData = Videos::where('video_type', $video_type)
                    ->where('course_id', $course_id)
                    ->orderByDesc('id')
                    ->get();
            } else {
                if (!VideosCategory::where('id', $category_id)->exists()) {
                    $data['message'] = base64_encode(base64_encode('This Category Does Not Exists!'));
                    return response()->json($data, 210);
                }

                if ($course_id == 0) {
                    $mainData = Videos::where('course_id', $course_id)->where('video_type', $video_type)->where('category_id', $category_id)->orderByDesc('id')->get();
                } else {
                    if (!PaidCourses::where('id', $course_id)->where('is_active', 0)->exists()) {
                        $data['message'] = base64_encode(base64_encode('This Course Does Not Exists!'));
                        return response()->json($data, 210);
                    }
                    $mainData = Videos::where('course_id', $course_id)->where('category_id', $category_id)->where('video_type', $video_type)->orderByDesc('id')->get();
                }
            }

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($mainData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getEbooksData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $model = new Ebooks();
            $model::$uid = $uid;
            $appData = $model::orderByDesc('id')->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getSyllabusData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $syllabusData = Syllabus::orderByDesc('id')->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($syllabusData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getNotificationsData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $appData = Notifications::orderByDesc('id')->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getUserProfileData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $appData = Users::where('uid', $uid)->first();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function saveUserProfileData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $username = $jsondata['username'];
            $email = $jsondata['email'];
            $state = $jsondata['state'];
            $password = $jsondata['password'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $user = Users::where('uid', $uid)->first();
            $profile_pic = $user->profile_pic;

            $files = $req->file();

            if (count($files) == 0) {
                $profile_pic = $user->profile_pic;
            } else {
                foreach ($files as $file) {
                    $extension = $file->extension();
                    $path = Storage::disk('public')->putFileAs('', $file, Str::random(10) . '.' . $extension, 'public');
                    $profile_pic = asset(Storage::url($path));
                }
            }

            $user->username = $username;
            $user->email = $email;
            $user->state = $state;
            $user->password = base64_encode(base64_encode($password));
            $user->profile_pic = $profile_pic;
            $user->save();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($user)));
            $data['message'] = base64_encode(base64_encode('User Profile Updated Successfully'));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getPaidCourse(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $course_id = $jsondata['course_id'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if (!PaidCourses::where('id', $course_id)->where('is_active', 0)->exists()) {
                $data['message'] = base64_encode(base64_encode('This Course Does Not Exists!'));
                return response()->json($data, 210);
            }

            $model = new PaidCourses();
            $model::$uid = $uid;
            $appData = $model::where('id', $course_id)->where('is_active', 0)->first();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getMockTestCategoryData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $model = new MockTestCategory();
            $model::$uid = $uid;
            $appData = $model::orderByDesc('id')->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getMockTestData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $type = $jsondata['type'];
            $test_category_id = $jsondata['test_category_id'];
            $course_id = $jsondata['course_id'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if ($type == 1) {
                if (!PaidCourses::where('id', $course_id)->where('is_active', 0)->exists()) {
                    $data['data'] = [];
                    $data['message'] = base64_encode(base64_encode('This Paid Course Does Not Exists!'));
                    return response()->json($data, 210);
                }
                $appData = MockTests::where('type', $type)->where('course_id', $course_id)->get();
            } else {
                if (!MockTestCategory::where('id', $test_category_id)->exists()) {
                    $data['data'] = [];
                    $data['message'] = base64_encode(base64_encode('This Mock Test Does Not Exists!'));
                    return response()->json($data, 210);
                }
                $appData = MockTests::where('type', $type)->where('test_category_id', $test_category_id)->get();
            }

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getMockTestQuestionsData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $mock_test_id = $jsondata['mock_test_id'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if (!MockTests::where('id', $mock_test_id)->exists()) {
                $data['message'] = base64_encode(base64_encode('This Mock Test Does Not Exists!'));
                return response()->json($data, 210);
            }

            $appData = TestQuestions::where('mock_test_id', $mock_test_id)->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getUserOrdersData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $type = $jsondata['type'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $ids = UserOrders::where('user_uid', $uid)->where('type', $type)
                ->select('details')->pluck('details');

            $appData = [];
            if ($type == 0) {
                $model = new PaidCourses();
                $model::$uid = $uid;
                $appData = $model::whereIn('id', $ids)->where('is_active', 0)->get();
            } else if ($type == 1) {
                $model = new Ebooks();
                $model::$uid = $uid;
                $appData = $model::whereIn('id', $ids)->get();
            } else if ($type == 2) {
                $model = new MockTestCategory();
                $model::$uid = $uid;
                $appData = $model::whereIn('id', $ids)->get();
            }

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getPDFNotesData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $course_id = $jsondata['course_id'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if (!PaidCourses::where('id', $course_id)->where('is_active', 0)->exists()) {
                $data['message'] = base64_encode(base64_encode('This Paid Course Does Not Exists!'));
                return response()->json($data, 210);
            }

            $appData = PDFNotes::where('course_id', $course_id)->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getUpcomingLiveVideosData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['data'] = [];
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $ids = UserOrders::where('user_uid', $uid)->where('type', 0)->select('details')->pluck('details');

            if (!PaidCourses::whereIn('id', $ids)->exists()) {
                $data['data'] = [];
                $data['message'] = base64_encode(base64_encode('No Live Videos Found'));
                return response()->json($data, 210);
            }

            $mainData = Videos::where('video_type', 1)
                ->whereIn('course_id', $ids)
                ->orderByDesc('id')
                ->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($mainData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function getCoupanCodesData(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $appData = Coupans::orderByDesc('id')->get();

            $data = [];
            $data['data'] = base64_encode(base64_encode(json_encode($appData)));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function createInstamojoPaymentRequest(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $price = $jsondata['price'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $INSTAMOJO_Client_ID = AppSetting::first()->INSTAMOJO_Client_ID;
            $INSTAMOJO_Client_Secret = AppSetting::first()->INSTAMOJO_Client_Secret;
            $response = Http::post('https://api.instamojo.com/oauth2/token/', [
                'grant_type' => 'client_credentials',
                'client_id' => base64_decode(base64_decode($INSTAMOJO_Client_ID)),
                'client_secret' => base64_decode(base64_decode($INSTAMOJO_Client_Secret)),
            ]);

            $result = json_decode($response, true);

            if (array_key_exists('error', $result)) {
                $data['message'] = base64_encode(base64_encode('Error - ' . $result['error']));
                return response()->json($data, 200);
            }

            $access_token = $result['access_token'];

            $user = Users::where('uid', $uid)->first();

            if (strlen($user->username) == 0) {
                $name = "Unkown";
            } else {
                $name = $user->username;
            }

            if (strlen($user->email) == 0) {
                $email = "Unkown@gmail.com";
            } else {
                $name = $user->email;
            }

            $paymentResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
            ])->post('https://api.instamojo.com/v2/payment_requests/', [
                'amount' => '' . $price,
                'purpose' => 'App Purchase',
                'buyer_name' => $name,
                'email' => $email,
                'phone' => $user->phone_number,
                'redirect_url' => '',
                'webhook' => '',
                'allow_repeated_payments' => false,
                'send_email' => false,
                'send_sms' => false,
            ]);

            $result2 = json_decode($paymentResponse, true);

            $orderResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
            ])->post('https://api.instamojo.com/v2/gateway/orders/payment-request/', [
                'id' => $result2['id'],
            ]);

            $data = [];
            $data['data'] = base64_encode(base64_encode($orderResponse));

            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function createPhonePePaymentRequest(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $price = $jsondata['price'];
            $app_package = $jsondata['app_package'];
            $intent_type = $jsondata['intent_type'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $user = Users::where('uid', $uid)->first();

            $transID = Str::upper(Str::random(16));
            $muid = Str::upper(Str::random(5));

            $settings = AppSetting::first();

            $merchantId = $settings->phonepe_merchant_id;
            $merchantTransactionId = 'MT' . $transID;
            $merchantUserId =  'MU' . $user->phone_number . $muid;
            $mobileNumber = $user->phone_number;
            $deviceContext = [
                'deviceOS' => 'ANDROID',
            ];

            if ($intent_type == 1) {
                $paymentInstrument = [
                    'type' => 'UPI_QR'
                ];
            } else if ($intent_type == 2) {
                $paymentInstrument = [
                    'type' => 'PAY_PAGE',
                ];
            } else {
                $paymentInstrument = [
                    'type' => 'UPI_INTENT',
                    'targetApp' => $app_package
                ];
            }

            $payload = [
                'merchantId' => $merchantId,
                'merchantTransactionId' => $merchantTransactionId,
                'merchantUserId' => $merchantUserId,
                'amount' => (((int)$price) * 100),
                "redirectUrl" => route('checkPayment'),
                "redirectMode" => "REDIRECT",
                'callbackUrl' => route('createPaymentCallback'),
                'mobileNumber' => $mobileNumber,
                'deviceContext' => $deviceContext,
                'paymentInstrument' => $paymentInstrument,
            ];

            $encodedData = base64_encode(json_encode($payload));

            $string = $encodedData . '/pg/v1/pay' . $settings->phonepe_salt_key;
            $sha256 = hash('sha256', $string);

            $requestData = [
                'request' => $encodedData,
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $settings->phonepe_host_url . "/pg/v1/pay");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-VERIFY: ' . $sha256 . '###' . $settings->phonepe_salt_index,
                'Accept: application/json',
            ]);

            $server_ip_address = AppSetting::first()->server_ip_address;
            curl_setopt($ch, CURLOPT_INTERFACE, $server_ip_address);

            $response = curl_exec($ch);

            if ($response === false) {
                echo 'cURL error: ' . curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode === 200) {
                    $responseData = json_decode($response, true);
                    echo json_encode($responseData);
                } else {
                    echo json_encode(['error' => $response]);
                }
            }

            curl_close($ch);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function checkPhonePePaymentStatus(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $merchantTransactionId = $jsondata['merchantTransactionId'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            $user = Users::where('uid', $uid)->first();

            $settings = AppSetting::first();

            $merchantId = $settings->phonepe_merchant_id;

            $string = '/pg/v1/status/' . $merchantId . '/' . $merchantTransactionId . $settings->phonepe_salt_key;
            $sha256 = hash('sha256', $string);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $settings->phonepe_host_url . '/pg/v1/status/' . $merchantId . '/' . $merchantTransactionId);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-MERCHANT-ID: ' . $merchantId,
                'X-VERIFY: ' . $sha256 . '###' . $settings->phonepe_salt_index,
                'Accept: application/json',
            ]);

            $server_ip_address = AppSetting::first()->server_ip_address;
            curl_setopt($ch, CURLOPT_INTERFACE, $server_ip_address);

            $response = curl_exec($ch);

            if ($response === false) {
                echo 'cURL error: ' . curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode === 200) {
                    $responseData = json_decode($response, true);
                    echo json_encode($responseData);
                } else {
                    echo json_encode(['error' => $response]);
                }
            }

            curl_close($ch);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function createPaymentCallback(Request $req)
    {
        Log::debug(json_encode($req));
        return "Done";
    }

    function purchaseItem(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $details = $jsondata['details'];
            $price = $jsondata['price'];
            $transaction_details = $jsondata['transaction_details'];
            $payment_method = $jsondata['payment_method'];
            $type = $jsondata['type'];
            $coupan_used = $jsondata['coupan_used'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if (UserOrders::where('user_uid', $uid)->where('type', $type)->where('details', $details)->exists()) {
                $data['message'] = base64_encode(base64_encode('You Have Already Purchased This Item!'));
                return response()->json($data, 210);
            }

            if ($coupan_used != 0) {
                if (!Coupans::where('id', $coupan_used)->exists()) {
                    $data['message'] = base64_encode(base64_encode('This Coupan Code Does Not Exists!'));
                    return response()->json($data, 210);
                }

                // $coupan = Coupans::where("id", $coupan_used)->first();

                // if ($coupan->uses >= $coupan->max_uses) {
                //     $data['message'] = base64_encode(base64_encode('This Coupan Code Has Reached Maximum Usage!'));
                //     return response()->json($data, 210);
                // }
            }

            UserOrders::create([
                'user_uid' => $uid,
                'details' => $details,
                'price' => $price,
                'type' => $type,
                'coupan_used' => $coupan_used,
                'transaction_details' => $transaction_details,
                'payment_method' => $payment_method,
                'created' => now(),
            ]);

            $data = [];
            $data['message'] = base64_encode(base64_encode("Purchase Successful!"));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

    function createManualPaymentRequest(Request $req)
    {
        $jsondata = json_decode(base64_decode(base64_decode(request()->data)), true);

        if (array_key_exists('uid', $jsondata)) {
            $uid = $jsondata['uid'];
            $transaction_id = $jsondata['transaction_id'];
            $amount = $jsondata['amount'];
            $r_data = $jsondata['data'];

            if (!Users::where('uid', $uid)->exists()) {
                $data['message'] = base64_encode(base64_encode('No User Found!'));
                return response()->json($data, 210);
            }

            if (ManualPaymentRequests::where('transaction_id', $transaction_id)->exists()) {
                $data['message'] = base64_encode(base64_encode('Duplicate Request!'));
                return response()->json($data, 210);
            }

            ManualPaymentRequests::create([
                'user_uid' => $uid,
                'transaction_id' => $transaction_id,
                'amount' => $amount,
                'data' => $r_data,
                'created' => now(),
            ]);

            $data = [];
            $data['message'] = base64_encode(base64_encode("Manual Payment Requests Successful! Please Wait For 24 Hrs For Verification"));
            return response()->json($data, 200);
        } else {
            $data['message'] = base64_encode(base64_encode('Required Fields Missing'));
            return response()->json($data, 210);
        }
    }

}
