<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Banners;
use App\Models\AppSetting;
use App\Models\Coupans;
use App\Models\Ebooks;
use App\Models\ManualPaymentRequests;
use App\Models\MockTestCategory;
use App\Models\MockTests;
use App\Models\Notifications;
use App\Models\PaidCourses;
use App\Models\PDFNotes;
use App\Models\Syllabus;
use App\Models\TestQuestions;
use App\Models\UserOrders;
use App\Models\Users;
use App\Models\Videos;
use App\Models\VideosCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use OneSignal;
use PHPUnit\Framework\MockObject\MockType;
use Psy\Readline\Hoa\Console;
use GuzzleHttp\Client;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Schema;

class AdminPanelController extends Controller
{

    function loginPage()
    {
        if (session()->has('email')) {
            return redirect(route('users'));
        } else {
            return view('adminPanel/login', ['is_error' => false, 'error' => ""]);
        }
    }

    function loginUser(Request $req)
    {
        $input = $req->input();

        $type = $input['adminTypeSelect'];

        if (!Admins::where('admin_type', $type)->exists()) {
            return view('adminPanel/login', ['is_error' => true, 'error' => "Admin Not Exists"]);
        }
        $admins = Admins::where('admin_type', $type)->get();

        foreach ($admins as $ad) {
            if ($ad['email'] == $input['email']) {
                if (base64_decode($ad['password']) != $input['password']) {
                    return view('adminPanel/login', ['is_error' => true, 'error' => "Wrong Password"]);
                } else {
                    $req->session()->put('email', $input['email']);
                    $req->session()->put('admin_type', $type);
                    return redirect(route('paidCourses'));
                }
                break;
            }
        }

        return view('adminPanel/login', ['is_error' => true, 'error' => "No Email Matched"]);
    }

    function users(Request $req)
    {
        if (session()->has('email')) {
            $data = Users::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('users'));
            return view('adminPanel/users', ['activePage' => 'users', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function editUserItem(Request $req)
    {
        $data = [];

        $id = $req->input('id');

        $data = [];

        if ($id == null) {
            $data['message'] = 'User ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!Users::where('id', $id)->exists()) {
            $data['message'] = 'This User Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = Users::find($id);
        $item->points = $req->input('points', $item->points);
        $item->save();

        $data['success'] = true;
        $data['message'] = "User Edited Successfully";
        return back()->with($data);
    }

    function deleteUserItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "User ID Is Mandatory";
            return back()->with($data);
        }

        $item = Users::find($id);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "User Deleted Successfully";
        return back()->with($data);
    }

    function searchUser(Request $req)
    {
        if (session()->has('email')) {
            $term = $req->input('term');

            if ($term == 'blocked') {
                $data = Users::where('account_status', '1')
                    ->orderBy('id', 'DESC')->paginate(20);
            } else if ($term == 'active') {
                $data = Users::where('account_status', '0')
                    ->orderBy('id', 'DESC')->paginate(20);
            } else {
                $data = Users::where('uid', 'like', "%{$term}%")
                    ->orWhere('username', 'like', "%{$term}%")
                    ->orWhere('phone_number', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('created', 'like', "%{$term}%")
                    ->orderBy('id', 'DESC')
                    ->paginate(20);
            }

            $data->withPath(route('users'));
            return view('adminPanel/users', ['activePage' => 'users', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function blockUser(Request $req)
    {
        $data = [];

        $id = $req->input('id');

        $data = [];

        if ($id == null) {
            $data['message'] = 'User ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!Users::where('id', $id)->exists()) {
            $data['message'] = 'This User Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = Users::find($id);
        $item->account_status = 1;
        $item->save();

        $data['success'] = true;
        $data['message'] = "User Blocked Successfully";
        return back()->with($data);
    }

    function unBlockUser(Request $req)
    {
        $data = [];

        $id = $req->input('id');

        $data = [];

        if ($id == null) {
            $data['message'] = 'User ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!Users::where('id', $id)->exists()) {
            $data['message'] = 'This User Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = Users::find($id);
        $item->account_status = 0;
        $item->save();

        $data['success'] = true;
        $data['message'] = "User UnBlocked Successfully";
        return back()->with($data);
    }

    function paidCourses(Request $req)
    {
        if (session()->has('email')) {
            $data = PaidCourses::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('paidCourses'));
            return view('adminPanel/paidCourses', ['activePage' => 'paidCourses', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function editPaidCourseItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        $data = [];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!PaidCourses::where('id', $id)->exists()) {
            $data['message'] = 'This Paid Course Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = PaidCourses::find($id);
        $item->title = $req->input('title' . $id, $item->title);
        $item->description = $req->input('description' . $id, $item->description);
        $item->price = $req->input('price' . $id, $item->price);
        $item->is_active = $req->input('is_active' . $id, $item->is_active);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Paid Course Edited Successfully";
        return back()->with($data);
    }

    function addPaidCourseItem(Request $req)
    {
        $data = [];
        try {
            $title = $req->input()['title'];
            if ($title == null or strlen($title) == 0) {
                $data['success'] = false;
                $data['message'] = "Title Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Title Is Mandatory";
            return back()->with($data);
        }

        try {
            $description = $req->input()['description'];
            if ($description == null) {
                $data['success'] = false;
                $data['message'] = "Description Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Description Is Mandatory";
            return back()->with($data);
        }

        try {
            $price = $req->input()['price'];
            if ($price == null) {
                $data['success'] = false;
                $data['message'] = "Price Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Price Is Mandatory";
            return back()->with($data);
        }

        $imageUrl = $req->input('imageUrl', null);
        $imageFile = $req->file('image', null);

        if ($imageFile != null) {
            $extension = $imageFile->extension();
            $path = Storage::disk('public')->putFileAs('', $imageFile, Str::random(10) . '.' . $extension, 'public');
            $imageUrl = asset(Storage::url($path));
        }

        if (strlen($imageUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Course Image Is Mandatory";
            return back()->with($data);
        }

        PaidCourses::create([
            'title' => $title,
            'image' => $imageUrl,
            'description' => $description,
            'price' => $price
        ]);

        $data['success'] = true;
        $data['message'] = "Paid Course Added Successfully";
        return back()->with($data);
    }

    function deletePaidCourseItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = PaidCourses::find($id);
        $image = substr($item['image'], strripos($item['image'], "/") + 1);
        Storage::disk('public')->delete('' . $image);

        Videos::where('course_id', $item->id)->delete();

        $item->delete();

        $data['success'] = true;
        $data['message'] = "Paid Course Deleted Successfully";
        return back()->with($data);
    }

    function ebooks(Request $req)
    {
        if (session()->has('email')) {
            $data = Ebooks::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('ebooks'));
            return view('adminPanel/ebooks', ['activePage' => 'ebooks', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteEbookItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = Ebooks::find($id);
        $image = substr($item['image'], strripos($item['image'], "/") + 1);
        Storage::disk('public')->delete('' . $image);
        $file = substr($item['file'], strripos($item['file'], "/") + 1);
        Storage::disk('public')->delete('' . $file);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Ebook Deleted Successfully";
        return back()->with($data);
    }

    function addEbookItem(Request $req)
    {
        $data = [];
        try {
            $title = $req->input()['title'];
            if ($title == null or strlen($title) == 0) {
                $data['success'] = false;
                $data['message'] = "Title Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Title Is Mandatory";
            return back()->with($data);
        }

        try {
            $price = $req->input()['price'];
            if ($price == null) {
                $data['success'] = false;
                $data['message'] = "Price Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Price Is Mandatory";
            return back()->with($data);
        }

        $imageUrl = $req->input('imageUrl', null);
        $imageFile = $req->file('image', null);

        if ($imageFile != null) {
            $extension = $imageFile->extension();
            $path = Storage::disk('public')->putFileAs('', $imageFile, Str::random(10) . '.' . $extension, 'public');
            $imageUrl = asset(Storage::url($path));
        }

        if (strlen($imageUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Course Image Is Mandatory";
            return back()->with($data);
        }

        $pdfFile = $req->file('file', null);
        $pdfUrl = "";

        if ($pdfFile != null) {
            $extension = $pdfFile->extension();

            if ($extension != 'pdf') {
                $data['success'] = false;
                $data['message'] = "Ebook File Should Be PDF";
                return back()->with($data);
            }

            $path = Storage::disk('public')->putFileAs('', $pdfFile, Str::random(10) . '.' . $extension, 'public');
            $pdfUrl = asset(Storage::url($path));
        }

        if (strlen($pdfUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Ebook File Is Mandatory";
            return back()->with($data);
        }

        Ebooks::create([
            'title' => $title,
            'image' => $imageUrl,
            'file' => $pdfUrl,
            'price' => $price
        ]);

        $data['success'] = true;
        $data['message'] = "Ebook Added Successfully";
        return back()->with($data);
    }

    function editEbookItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!Ebooks::where('id', $id)->exists()) {
            $data['message'] = 'This Ebook Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = Ebooks::find($id);
        $item->title = $req->input('title' . $id, $item->title);
        $item->price = $req->input('price' . $id, $item->price);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Ebook Edited Successfully";
        return back()->with($data);
    }

    function banners(Request $req)
    {
        if (session()->has('email')) {
            $data = Banners::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('banners'));
            $courses = PaidCourses::orderBy('id', 'DESC')->get();
            return view('adminPanel/banners', ['activePage' => 'banners', 'data' => $data, 'courses' => $courses]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function addBannerItem(Request $req)
    {
        $data = [];
        try {
            $url = $req->input()['url'];
            if ($url == null or strlen($url) == 0) {
                $data['success'] = false;
                $data['message'] = "url Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "url Is Mandatory";
            return back()->with($data);
        }

        try {
            $type = $req->input()['bannerTypeSelect'];
            if ($type == null) {
                $data['success'] = false;
                $data['message'] = "Click Action Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Click Action Is Mandatory";
            return back()->with($data);
        }

        $imageUrl = $req->input('imageUrl', null);
        $imageFile = $req->file('image', null);

        if ($imageFile != null) {
            $extension = $imageFile->extension();
            $path = Storage::disk('public')->putFileAs('', $imageFile, Str::random(10) . '.' . $extension, 'public');
            $imageUrl = asset(Storage::url($path));
        }

        if (strlen($imageUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Banner Image Is Mandatory";
            return back()->with($data);
        }

        Banners::create([
            'image' => $imageUrl,
            'url' => $url,
            'type' => $type
        ]);

        $data['success'] = true;
        $data['message'] = "Banner Added Successfully";
        return back()->with($data);
    }

    function deleteBannerItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "Banner ID Is Mandatory";
            return back()->with($data);
        }

        $item = Banners::find($id);
        $image = substr($item['image'], strripos($item['image'], "/") + 1);
        Storage::disk('public')->delete('' . $image);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Banner Deleted Successfully";
        return back()->with($data);
    }

    function syllabus(Request $req)
    {
        if (session()->has('email')) {
            $data = Syllabus::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('syllabus'));
            return view('adminPanel/syllabus', ['activePage' => 'syllabus', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteSyllabusItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = Syllabus::find($id);
        $file = substr($item['file'], strripos($item['file'], "/") + 1);
        Storage::disk('public')->delete('' . $file);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Syllabus Deleted Successfully";
        return back()->with($data);
    }

    function addSyllabusItem(Request $req)
    {
        $data = [];
        try {
            $title = $req->input()['title'];
            if ($title == null or strlen($title) == 0) {
                $data['success'] = false;
                $data['message'] = "Title Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Title Is Mandatory";
            return back()->with($data);
        }

        $pdfFile = $req->file('file', null);
        $pdfUrl = "";

        if ($pdfFile != null) {
            $extension = $pdfFile->extension();

            if ($extension != 'pdf') {
                $data['success'] = false;
                $data['message'] = "Ebook File Should Be PDF";
                return back()->with($data);
            }

            $path = Storage::disk('public')->putFileAs('', $pdfFile, Str::random(10) . '.' . $extension, 'public');
            $pdfUrl = asset(Storage::url($path));
        }

        if (strlen($pdfUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Syllabus File Is Mandatory";
            return back()->with($data);
        }

        Syllabus::create([
            'title' => $title,
            'file' => $pdfUrl
        ]);

        $data['success'] = true;
        $data['message'] = "Syllabus Added Successfully";
        return back()->with($data);
    }

    function video_categories(Request $req)
    {
        if (session()->has('email')) {
            $data = VideosCategory::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('video_categories'));
            return view('adminPanel/video_categories', ['activePage' => 'video_categories', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function addVideoCategoryItem(Request $req)
    {
        $data = [];
        try {
            $category = $req->input()['category'];
            if ($category == null or strlen($category) == 0) {
                $data['success'] = false;
                $data['message'] = "Category Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Category Is Mandatory";
            return back()->with($data);
        }

        VideosCategory::create([
            'category' => $category,
        ]);

        $data['success'] = true;
        $data['message'] = "Video Category Added Successfully";
        return back()->with($data);
    }

    function deleteVideoCategoryItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = VideosCategory::find($id);

        Videos::where('category_id', $item->id)->delete();

        $item->delete();

        $data['success'] = true;
        $data['message'] = "Video Category Deleted Successfully";
        return back()->with($data);
    }

    function editVideoCategoryItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!VideosCategory::where('id', $id)->exists()) {
            $data['message'] = 'This Video Category Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = VideosCategory::find($id);
        $item->category = $req->input('category' . $id, $item->category);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Video Category Edited Successfully";
        return back()->with($data);
    }

    function videos(Request $req)
    {
        if (session()->has('email')) {
            $data = Videos::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('videos'));
            $course = PaidCourses::orderBy('id', 'DESC')->get();
            $categories = VideosCategory::orderBy('id', 'DESC')->get();
            return view('adminPanel/videos', ['activePage' => 'videos', 'data' => $data, 'course' => $course, 'categories' => $categories]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteVideosItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = Videos::find($id);
        $image = substr($item['image'], strripos($item['image'], "/") + 1);
        Storage::disk('public')->delete('' . $image);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Video Deleted Successfully";
        return back()->with($data);
    }

    function addVideosItem(Request $req)
    {
        $data = [];

        $title = $req->get('title', '');
        if ($title == null or strlen($title) == 0) {
            $data['success'] = false;
            $data['message'] = "Title Is Mandatory";
            return back()->with($data);
        }
        $video_link = $req->get('video_link', '');
        if ($video_link == null or strlen($video_link) == 0) {
            $data['success'] = false;
            $data['message'] = "video_link Is Mandatory";
            return back()->with($data);
        }
        $description = $req->get('description', '');
        if ($description == null or strlen($description) == 0) {
            $data['success'] = false;
            $data['message'] = "description Is Mandatory";
            return back()->with($data);
        }
        $video_type = $req->get('video_type', '0');
        if ($video_type == null or strlen($video_type) == 0) {
            $data['success'] = false;
            $data['message'] = "video_type Is Mandatory";
            return back()->with($data);
        }
        $paid = $req->get('paid', '0');
        if ($paid == null or strlen($paid) == 0) {
            $data['success'] = false;
            $data['message'] = "paid Is Mandatory";
            return back()->with($data);
        }
        $course_id = $req->get('course_id', '0');
        if ($course_id == null or strlen($course_id) == 0) {
            $data['success'] = false;
            $data['message'] = "course_id Is Mandatory";
            return back()->with($data);
        }
        $category_id = $req->get('category_id', '0');
        if ($category_id == null or strlen($category_id) == 0) {
            $data['success'] = false;
            $data['message'] = "category_id Is Mandatory";
            return back()->with($data);
        }

        $imageUrl = $req->input('imageUrl', "");
        $imageFile = $req->file('image', null);

        if ($imageFile != null) {
            $extension = $imageFile->extension();
            $path = Storage::disk('public')->putFileAs('', $imageFile, Str::random(10) . '.' . $extension, 'public');
            $imageUrl = asset(Storage::url($path));
        }

        if (strlen($imageUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Video Image Is Mandatory";
            return back()->with($data);
        }

        Videos::create([
            'title' => $title,
            'video_link' => $video_link,
            'description' => $description,
            'video_type' => $video_type,
            'paid' => $paid,
            'category_id' => $category_id,
            'course_id' => $course_id,
            'image' => $imageUrl,
        ]);

        $data['success'] = true;
        $data['message'] = "Video Added Successfully";
        return back()->with($data);
    }

    function editVideoItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!Videos::where('id', $id)->exists()) {
            $data['message'] = 'This Video Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = Videos::find($id);
        $item->title = $req->input('title' . $id, $item->title);
        $item->description = $req->input('description' . $id, $item->description);
        $item->video_link = $req->input('video_link' . $id, $item->video_link);
        $item->video_type = $req->input('video_type' . $id, $item->video_type);
        $item->paid = $req->input('paid' . $id, $item->paid);
        $item->category_id = $req->input('category_id' . $id, $item->category_id);
        $item->course_id = $req->input('course_id' . $id, $item->course_id);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Video Category Edited Successfully";
        return back()->with($data);
    }

    function mock_test_category(Request $req)
    {
        if (session()->has('email')) {
            $data = MockTestCategory::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('mock_test_category'));
            return view('adminPanel/mock_test_category', ['activePage' => 'mock_test_category', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteMockTestCategoryItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = MockTestCategory::find($id);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Mock Test Category Deleted Successfully";
        return back()->with($data);
    }

    function addMockTestCategoryItem(Request $req)
    {
        $data = [];

        $title = $req->get('title', '');
        if ($title == null or strlen($title) == 0) {
            $data['success'] = false;
            $data['message'] = "Title Is Mandatory";
            return back()->with($data);
        }

        $price = $req->get('price', '');
        if ($price == null or strlen($price) == 0) {
            $data['success'] = false;
            $data['message'] = "Price Is Mandatory";
            return back()->with($data);
        }

        MockTestCategory::create([
            'title' => $title,
            'price' => $price
        ]);

        $data['success'] = true;
        $data['message'] = "Mock Test Category Added Successfully";
        return back()->with($data);
    }

    function editMockTestCategoryItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!MockTestCategory::where('id', $id)->exists()) {
            $data['message'] = 'This Mock Test Category Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = MockTestCategory::find($id);
        $item->title = $req->input('title' . $id, $item->title);
        $item->price = $req->input('price' . $id, $item->price);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Mock Test Category Edited Successfully";
        return back()->with($data);
    }

    function mock_tests(Request $req)
    {
        if (session()->has('email')) {
            $data = MockTests::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('mock_tests'));
            $course = PaidCourses::orderBy('id', 'DESC')->get();
            $categories = MockTestCategory::orderBy('id', 'DESC')->get();
            return view('adminPanel/mock_tests', ['activePage' => 'mock_tests', 'data' => $data, 'categories' => $categories, 'course' => $course]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteMockTestItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = MockTests::find($id);
        TestQuestions::where('mock_test_id', $item->id)->delete();
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Mock Test Deleted Successfully";
        return back()->with($data);
    }

    function addMockTestItem(Request $req)
    {
        $data = [];

        $title = $req->get('title', '');
        if ($title == null or strlen($title) == 0) {
            $data['success'] = false;
            $data['message'] = "Title Is Mandatory";
            return back()->with($data);
        }

        $test_category_id = $req->get('test_category_id', '');
        if ($test_category_id == null or strlen($test_category_id) == 0) {
            $data['success'] = false;
            $data['message'] = "Test Category Id Is Mandatory";
            return back()->with($data);
        }

        $test_time = $req->get('test_time', '');
        if ($test_time == null or strlen($test_time) == 0) {
            $data['success'] = false;
            $data['message'] = "Test Time Is Mandatory";
            return back()->with($data);
        }

        $course_id = $req->get('course_id', 0);
        $type = $req->get('type', 0);

        MockTests::create([
            'title' => $title,
            'test_category_id' => $test_category_id,
            'course_id' => $course_id,
            'type' => $type,
            'test_time' => $test_time
        ]);

        $data['success'] = true;
        $data['message'] = "Mock Test Added Successfully";
        return back()->with($data);
    }

    function editMockTestItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!MockTests::where('id', $id)->exists()) {
            $data['message'] = 'This Mock Test Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = MockTests::find($id);
        $item->title = $req->input('title' . $id, $item->title);
        $item->test_category_id = $req->input('test_category_id' . $id, $item->test_category_id);
        $item->course_id = $req->input('course_id' . $id, $item->course_id);
        $item->type = $req->input('type' . $id, $item->type);
        $item->test_time = $req->input('test_time' . $id, $item->test_time);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Mock Test Edited Successfully";
        return back()->with($data);
    }

    function searchMockTestItem(Request $req)
    {
        if (session()->has('email')) {
            $term = $req->input('term');

            $matchRecords = MockTestCategory::where('title', 'like', "%{$term}%")->select('id')->pluck('id');

            $data = MockTests::whereIn('test_category_id', $matchRecords)
                ->orderBy('id', 'DESC')
                ->paginate(20);

            $data->withPath(route('mock_tests'));
            $course = PaidCourses::orderBy('id', 'DESC')->get();
            $categories = MockTestCategory::orderBy('id', 'DESC')->get();
            return view('adminPanel/mock_tests', ['activePage' => 'mock_tests', 'data' => $data, 'categories' => $categories, 'course' => $course]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function test_questions(Request $req)
    {
        if (session()->has('email')) {
            $data = TestQuestions::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('test_questions'));
            $mockTests = MockTests::orderBy('id', 'DESC')->get();
            return view('adminPanel/test_questions', ['activePage' => 'test_questions', 'data' => $data, 'mockTests' => $mockTests]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteTestQuestionsItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = TestQuestions::find($id);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Test Question Deleted Successfully";
        return back()->with($data);
    }

    function addTestQuestionsItem(Request $req)
    {
        $data = [];

        $mock_test_id = $req->get('mock_test_id', '');
        if ($mock_test_id == null or strlen($mock_test_id) == 0) {
            $data['success'] = false;
            $data['message'] = "mock_test_id Is Mandatory";
            return back()->with($data);
        }

        $question = $req->get('question', '');
        if ($question == null or strlen($question) == 0) {
            $data['success'] = false;
            $data['message'] = "question Is Mandatory";
            return back()->with($data);
        }

        $opt_1 = $req->get('opt_1', '');
        if ($opt_1 == null or strlen($opt_1) == 0) {
            $data['success'] = false;
            $data['message'] = "opt_1 Is Mandatory";
            return back()->with($data);
        }

        $opt_2 = $req->get('opt_2', '');
        if ($opt_2 == null or strlen($opt_2) == 0) {
            $data['success'] = false;
            $data['message'] = "opt_2 Is Mandatory";
            return back()->with($data);
        }

        $opt_3 = $req->get('opt_3', '');
        if ($opt_3 == null or strlen($opt_3) == 0) {
            $data['success'] = false;
            $data['message'] = "opt_3 Is Mandatory";
            return back()->with($data);
        }

        $opt_4 = $req->get('opt_4', '');
        if ($opt_4 == null or strlen($opt_4) == 0) {
            $data['success'] = false;
            $data['message'] = "opt_4 Is Mandatory";
            return back()->with($data);
        }

        $correct_option_no = $req->get('correct_option_no', '');
        if ($correct_option_no == null or strlen($correct_option_no) == 0) {
            $data['success'] = false;
            $data['message'] = "correct_option_no Is Mandatory";
            return back()->with($data);
        }

        TestQuestions::create([
            'mock_test_id' => $mock_test_id,
            'question' => $question,
            'opt_1' => $opt_1,
            'opt_2' => $opt_2,
            'opt_3' => $opt_3,
            'opt_4' => $opt_4,
            'correct_option_no' => $correct_option_no,
        ]);

        $data['success'] = true;
        $data['message'] = "Test Question Added Successfully";
        return back()->with($data);
    }

    function editTestQuestionsItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!TestQuestions::where('id', $id)->exists()) {
            $data['message'] = 'This Test Question Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = TestQuestions::find($id);
        $item->mock_test_id = $req->input('mock_test_id' . $id, $item->mock_test_id);
        $item->question = $req->input('question' . $id, $item->question);
        $item->opt_1 = $req->input('opt_1' . $id, $item->opt_1);
        $item->opt_2 = $req->input('opt_2' . $id, $item->opt_2);
        $item->opt_3 = $req->input('opt_3' . $id, $item->opt_3);
        $item->opt_4 = $req->input('opt_4' . $id, $item->opt_4);
        $item->correct_option_no = $req->input('correct_option_no' . $id, $item->correct_option_no);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Test Question Edited Successfully";
        return back()->with($data);
    }

    function searchTestQuestionsItem(Request $req)
    {
        if (session()->has('email')) {
            $term = $req->input('term');

            $matchRecords = MockTests::where('title', 'like', "%{$term}%")->select('id')->pluck('id');

            $data = TestQuestions::whereIn('mock_test_id', $matchRecords)
                ->orWhere('question', 'like', "%{$term}%")
                ->orderBy('id', 'DESC')
                ->paginate(20);

            $data->withPath(route('test_questions'));
            $mockTests = MockTests::orderBy('id', 'DESC')->get();
            return view('adminPanel/test_questions', ['activePage' => 'test_questions', 'data' => $data, 'mockTests' => $mockTests]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function pdf_notes(Request $req)
    {
        if (session()->has('email')) {
            $data = PDFNotes::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('pdf_notes'));
            $course = PaidCourses::orderBy('id', 'DESC')->get();
            return view('adminPanel/pdf_notes', ['activePage' => 'pdf_notes', 'data' => $data, 'course' => $course]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deletePdfNotesItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = PDFNotes::find($id);
        $file = substr($item['file'], strripos($item['file'], "/") + 1);
        Storage::disk('public')->delete('' . $file);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "PDF Notes Deleted Successfully";
        return back()->with($data);
    }

    function addPdfNotesItem(Request $req)
    {
        $data = [];

        $title = $req->get('title', '');
        if ($title == null or strlen($title) == 0) {
            $data['success'] = false;
            $data['message'] = "title Is Mandatory";
            return back()->with($data);
        }

        $course_id = $req->get('course_id', '');
        if ($course_id == null or strlen($course_id) == 0) {
            $data['success'] = false;
            $data['message'] = "course_id Is Mandatory";
            return back()->with($data);
        }

        $pdfFile = $req->file('file', null);
        $pdfUrl = "";

        if ($pdfFile != null) {
            $extension = $pdfFile->extension();

            if ($extension != 'pdf') {
                $data['success'] = false;
                $data['message'] = "Ebook File Should Be PDF";
                return back()->with($data);
            }

            $path = Storage::disk('public')->putFileAs('', $pdfFile, Str::random(10) . '.' . $extension, 'public');
            $pdfUrl = asset(Storage::url($path));
        }

        if (strlen($pdfUrl) ==  0) {
            $data['success'] = false;
            $data['message'] = "Syllabus File Is Mandatory";
            return back()->with($data);
        }

        PDFNotes::create([
            'title' => $title,
            'file' => $pdfUrl,
            'course_id' => $course_id,
        ]);

        $data['success'] = true;
        $data['message'] = "PDF Notes Added Successfully";
        return back()->with($data);
    }

    function editPdfNotesItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!PDFNotes::where('id', $id)->exists()) {
            $data['message'] = 'This PDF Notes Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = PDFNotes::find($id);
        $item->title = $req->input('title' . $id, $item->title);
        $item->course_id = $req->input('course_id' . $id, $item->course_id);
        $item->save();

        $data['success'] = true;
        $data['message'] = "PDF Notes Edited Successfully";
        return back()->with($data);
    }

    function coupan_codes(Request $req)
    {
        if (session()->has('email')) {
            $data = Coupans::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('coupan_codes'));
            return view('adminPanel/coupan_codes', ['activePage' => 'coupan_codes', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function addCoupanCodesItem(Request $req)
    {
        $data = [];

        $code = $req->get('code', '');
        if ($code == null or strlen($code) == 0) {
            $data['success'] = false;
            $data['message'] = "code Is Mandatory";
            return back()->with($data);
        }

        $discount_amount = $req->get('discount_amount', '');
        if ($discount_amount == null or strlen($discount_amount) == 0) {
            $data['success'] = false;
            $data['message'] = "discount_amount Is Mandatory";
            return back()->with($data);
        }

        Coupans::create([
            'code' => $code,
            'discount_amount' => $discount_amount,
        ]);

        $data['success'] = true;
        $data['message'] = "Coupan Added Successfully";
        return back()->with($data);
    }

    function deleteCoupanCodesItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = Coupans::find($id);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Coupan Deleted Successfully";
        return back()->with($data);
    }

    function editCoupanCodesItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!Coupans::where('id', $id)->exists()) {
            $data['message'] = 'This Coupan Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = Coupans::find($id);
        $item->code = $req->input('code' . $id, $item->code);
        $item->discount_amount = $req->input('discount_amount' . $id, $item->discount_amount);
        $item->save();

        $data['success'] = true;
        $data['message'] = "Coupan Edited Successfully";
        return back()->with($data);
    }

    function user_orders(Request $req)
    {
        if (session()->has('email')) {
            $data = UserOrders::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('user_orders'));
            return view('adminPanel/user_orders', ['activePage' => 'user_orders', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteUserOrdersItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = UserOrders::find($id);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "User Order Deleted Successfully";
        return back()->with($data);
    }

    function searchUserOrderItem(Request $req)
    {
        if (session()->has('email')) {
            $term = $req->input('term');

            $matchRecords = Users::where('uid', 'like', "%{$term}%")
                ->orWhere('username', 'like', "%{$term}%")
                ->orWhere('phone_number', 'like', "%{$term}%")
                ->select('uid')
                ->pluck('uid');

            $data = UserOrders::whereIn('user_uid', $matchRecords)
                ->orWhere('details', 'like', "%{$term}%")
                ->orWhere('type', 'like', "%{$term}%")
                ->orderBy('id', 'DESC')
                ->paginate(20);

            $data->withPath(route('user_orders'));
            return view('adminPanel/user_orders', ['activePage' => 'user_orders', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function notification(Request $req)
    {
        if (session()->has('email')) {
            return view('adminPanel/notification', ['activePage' => 'notification']);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function sendNotification(Request $req)
    {
        $message = $req->input('message');
        $noticeTitle = $req->input('title');
        $noticeClickUrl = $req->input('clickUrl');
        $imageUrl = $req->input('imageUrl', null);
        $imageFile = $req->file('image', null);

        $data = [];

        if (strlen($message) == 0 || strlen($noticeTitle) == 0) {
            $data['success'] = false;
            $data['message'] = "Notice Title And Message Is Mandatory";
            return back()->with($data);
        }

        if ($imageFile != null) {
            $extension = $imageFile->extension();
            $path = Storage::disk('public')->putFileAs('', $imageFile, time() . '.' . $extension, 'public');
            $imageUrl = asset(Storage::url($path));
        }

        $credentialsJson = file_get_contents(base_path('firebase_credentials.json'));
        if ($credentialsJson === false) {
            $data['success'] = false;
            $data['message'] = "Failed to read the credentials file.";
            return back()->with($data);
        }
        $credentials = json_decode($credentialsJson, true);

        $now = time();
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $jwtClaimSet = base64_encode(json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]));

        $privateKey = openssl_pkey_get_private($credentials['private_key']);
        $signature = '';
        openssl_sign($jwtHeader . '.' . $jwtClaimSet, $signature, $privateKey, 'SHA256');
        $jwtSignature = base64_encode($signature);

        $jwt = $jwtHeader . '.' . $jwtClaimSet . '.' . $jwtSignature;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://oauth2.googleapis.com/token");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
        ));
        $response = curl_exec($curl);
        if ($response === false) {
            $data['success'] = false;
            $data['message'] = "cURL error: " . curl_error($curl);
            return back()->with($data);
        }
        $tokenData = json_decode($response, true);
        if (isset($tokenData['error'])) {
            $data['success'] = false;
            $data['message'] = "Error fetching access token: " . $tokenData['error_description'];
            return back()->with($data);
        }
        $accessToken = $tokenData['access_token'];
        curl_close($curl);

        $fcmData = [
            "message" => [
                "topic" => "all",
                "notification" => [
                    "title" => $noticeTitle,
                    "body" => $message,
                    "image" => $imageUrl
                ],
                "webpush" => [
                    "fcm_options" => [
                        "link" => $noticeClickUrl
                    ]
                ]
            ]
        ];

        $url = "https://fcm.googleapis.com/v1/projects/" . $credentials['project_id'] . "/messages:send";

        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $fcmData,
        ]);

        if ($response->getStatusCode() === 200) {
            $data['success'] = true;
            $data['message'] = "Notification Sent Successfully : " . json_encode($response);
            return back()->with($data);
        } else {
            $data['success'] = false;
            $data['message'] = "Failed to send notification : " . json_encode($response);
            return back()->with($data);
        }
    }

    function manual_payment_requests(Request $req)
    {
        if (session()->has('email')) {
            $data = ManualPaymentRequests::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('manual_payment_requests'));
            return view('adminPanel/manual_payment_requests', ['activePage' => 'manual_payment_requests', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function deleteManualRequestsItem(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        $item = ManualPaymentRequests::find($id);
        $item->delete();

        $data['success'] = true;
        $data['message'] = "Manual Payment Requests Deleted Successfully";
        return back()->with($data);
    }

    function editManualRequestsItem(Request $req)
    {
        $data = [];

        $id = $req->input()['id'];

        if ($id == null) {
            $data['message'] = 'ID Is Mandatory';
            $data['success'] = false;
            return back()->with($data);
        }

        if (!ManualPaymentRequests::where('id', $id)->exists()) {
            $data['message'] = 'This ManualPaymentRequests Does Not Exists';
            $data['success'] = false;
            return back()->with($data);
        }

        $item = ManualPaymentRequests::find($id);
        $status = $req->input('status' . $id, "");

        if (!empty($status)) {
            if ($status != $item->status && $status == 1) {
                if (Users::where('uid', $item->user_uid)->exists()) {
                    $user = Users::where('uid', $item->user_uid)->first();
                    $data = json_decode($item->data, true);
                    UserOrders::create([
                        'user_uid' => $item->user_uid,
                        'details' => $data['details'],
                        'price' => $data['price'],
                        'type' => $data['type'],
                        'coupan_used' => $data['coupan_used'],
                        'transaction_details' => $data['transaction_details'],
                        'payment_method' => $data['payment_method'],
                        'created' => now(),
                    ]);
                }
            }
        } else {
            $status = $item->status;
        }

        $item->status = $status;
        $item->save();

        $data['success'] = true;
        $data['message'] = "Manual Payment Requests Edited Successfully";
        return back()->with($data);
    }

    function web_admins(Request $req)
    {
        if (session()->has('email')) {
            $data = Admins::orderBy('id', 'DESC')->paginate(20);
            $data->withPath(route('banners'));
            return view('adminPanel/web_admins', ['activePage' => 'web_admins', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function addWebAdminItem(Request $req)
    {
        $data = [];
        try {
            $email = $req->input()['email'];
            if ($email == null or strlen($email) == 0) {
                $data['success'] = false;
                $data['message'] = "email Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "email Is Mandatory";
            return back()->with($data);
        }

        try {
            $password = $req->input()['password'];
            if ($password == null or strlen($password) == 0) {
                $data['success'] = false;
                $data['message'] = "password Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "password Is Mandatory";
            return back()->with($data);
        }

        try {
            $type = $req->input()['adminTypeSelect'];
            if ($type == null) {
                $data['success'] = false;
                $data['message'] = "Admin Type Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "Admin Type Is Mandatory";
            return back()->with($data);
        }

        Admins::create([
            'email' => $email,
            'password' => base64_encode($password),
            'admin_type' => $type
        ]);

        $data['success'] = true;
        $data['message'] = "Admin Added Successfully";
        return back()->with($data);
    }

    function deleteWebAdmin(Request $req)
    {
        $data = [];
        $id = $req->input()['id'];

        if ($id == null || strlen($id) == 0) {
            $data['success'] = false;
            $data['message'] = "Admin ID Is Mandatory";
            return back()->with($data);
        }

        $item = Admins::find($id);

        if ($item->admin_type == 0) {
            if (Admins::where('admin_type', 0)->count() <= 1) {
                $data['success'] = false;
                $data['message'] = "Atleast One Admin Is Required";
                return back()->with($data);
            }
        }

        $item->delete();

        $data['success'] = true;
        $data['message'] = "Admin Deleted Successfully";
        return back()->with($data);
    }

    function changeAdminPassword(Request $req)
    {
        $data = [];
        try {
            $id = $req->input()['id'];
            if ($id == null or strlen($id) == 0) {
                $data['success'] = false;
                $data['message'] = "ID Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "ID Is Mandatory";
            return back()->with($data);
        }

        try {
            $password = $req->input()['password' . $id];
            if ($password == null or strlen($password) == 0) {
                $data['success'] = false;
                $data['message'] = "password Is Mandatory";
                return back()->with($data);
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = "password Is Mandatory";
            return back()->with($data);
        }

        $admin = Admins::find($id);
        $admin->password = base64_encode($password);
        $admin->save();

        $data['success'] = true;
        $data['message'] = "Password Changed Successfully";
        return back()->with($data);
    }

    function app_settings(Request $req)
    {
        if (session()->has('email')) {
            $data = AppSetting::first()->toArray();
            return view('adminPanel/app_settings', ['activePage' => 'app_settings', 'data' => $data]);
        } else {
            return redirect(route('loginPage'));
        }
    }

    function saveSettings(Request $req)
    {
        $formData = $req->all();

        $setting = AppSetting::first();

        foreach ($formData as $key => $value) {
            if (Schema::hasColumn((new AppSetting)->getTable(), $key)) {
                $setting->$key = $value ?? "";
            }
        }

        $setting->save();

        $data['success'] = true;
        $data['message'] = "Settings Saved";
        return back()->with($data);
    }

    function logout()
    {
        session()->forget('email');
        session()->forget('admin_type');
        return view('adminPanel/login', ['is_error' => false, 'error' => ""]);
    }
}
