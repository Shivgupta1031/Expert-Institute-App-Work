<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AdminPanelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/privacypolicy', function () {
    return view('privacy_policy');
})->name('privacypolicy');

Route::get('/termsconditions', function () {
    return view('terms_conditions');
})->name('termsconditions');

// Admin Panel Routes
Route::get('/adminpanel/panel/', [AdminPanelController::class, 'loginPage'])->name('loginPage');

Route::post('/adminpanel/panel/loginUser', [AdminPanelController::class, 'loginUser'])->name('loginUser');

Route::get('/adminpanel/panel/users', [AdminPanelController::class, 'users'])->name('users');
Route::post('/adminpanel/panel/searchUser', [AdminPanelController::class, 'searchUser'])->name('searchUser');
Route::get('/adminpanel/panel/deleteUserItem', [AdminPanelController::class, 'deleteUserItem'])->name('deleteUserItem');
Route::get('/adminpanel/panel/editUserItem', [AdminPanelController::class, 'editUserItem'])->name('editUserItem');
Route::get('/adminpanel/panel/blockUser', [AdminPanelController::class, 'blockUser'])->name('blockUser');
Route::get('/adminpanel/panel/unBlockUser', [AdminPanelController::class, 'unBlockUser'])->name('unBlockUser');

Route::get('/adminpanel/panel/paidCourses', [AdminPanelController::class, 'paidCourses'])->name('paidCourses');
Route::get('/adminpanel/panel/deletePaidCourseItem', [AdminPanelController::class, 'deletePaidCourseItem'])->name('deletePaidCourseItem');
Route::post('/adminpanel/panel/editPaidCourseItem', [AdminPanelController::class, 'editPaidCourseItem'])->name('editPaidCourseItem');
Route::post('/adminpanel/panel/addPaidCourseItem', [AdminPanelController::class, 'addPaidCourseItem'])->name('addPaidCourseItem');

Route::get('/adminpanel/panel/ebooks', [AdminPanelController::class, 'ebooks'])->name('ebooks');
Route::get('/adminpanel/panel/deleteEbookItem', [AdminPanelController::class, 'deleteEbookItem'])->name('deleteEbookItem');
Route::post('/adminpanel/panel/editEbookItem', [AdminPanelController::class, 'editEbookItem'])->name('editEbookItem');
Route::post('/adminpanel/panel/addEbookItem', [AdminPanelController::class, 'addEbookItem'])->name('addEbookItem');

Route::get('/adminpanel/panel/banners', [AdminPanelController::class, 'banners'])->name('banners');
Route::get('/adminpanel/panel/deleteBannerItem', [AdminPanelController::class, 'deleteBannerItem'])->name('deleteBannerItem');
Route::post('/adminpanel/panel/addBannerItem', [AdminPanelController::class, 'addBannerItem'])->name('addBannerItem');

Route::get('/adminpanel/panel/syllabus', [AdminPanelController::class, 'syllabus'])->name('syllabus');
Route::get('/adminpanel/panel/deleteSyllabusItem', [AdminPanelController::class, 'deleteSyllabusItem'])->name('deleteSyllabusItem');
Route::post('/adminpanel/panel/addSyllabusItem', [AdminPanelController::class, 'addSyllabusItem'])->name('addSyllabusItem');
Route::post('/adminpanel/panel/editSyllabusItem', [AdminPanelController::class, 'editSyllabusItem'])->name('editSyllabusItem');

Route::get('/adminpanel/panel/videos', [AdminPanelController::class, 'videos'])->name('videos');
Route::get('/adminpanel/panel/deleteVideosItem', [AdminPanelController::class, 'deleteVideosItem'])->name('deleteVideosItem');
Route::post('/adminpanel/panel/addVideosItem', [AdminPanelController::class, 'addVideosItem'])->name('addVideosItem');
Route::post('/adminpanel/panel/editVideoItem', [AdminPanelController::class, 'editVideoItem'])->name('editVideoItem');

Route::get('/adminpanel/panel/video_categories', [AdminPanelController::class, 'video_categories'])->name('video_categories');
Route::get('/adminpanel/panel/deleteVideoCategoryItem', [AdminPanelController::class, 'deleteVideoCategoryItem'])->name('deleteVideoCategoryItem');
Route::post('/adminpanel/panel/addVideoCategoryItem', [AdminPanelController::class, 'addVideoCategoryItem'])->name('addVideoCategoryItem');
Route::post('/adminpanel/panel/editVideoCategoryItem', [AdminPanelController::class, 'editVideoCategoryItem'])->name('editVideoCategoryItem');

Route::get('/adminpanel/panel/mock_test_category', [AdminPanelController::class, 'mock_test_category'])->name('mock_test_category');
Route::get('/adminpanel/panel/deleteMockTestCategoryItem', [AdminPanelController::class, 'deleteMockTestCategoryItem'])->name('deleteMockTestCategoryItem');
Route::post('/adminpanel/panel/addMockTestCategoryItem', [AdminPanelController::class, 'addMockTestCategoryItem'])->name('addMockTestCategoryItem');
Route::post('/adminpanel/panel/editMockTestCategoryItem', [AdminPanelController::class, 'editMockTestCategoryItem'])->name('editMockTestCategoryItem');

Route::get('/adminpanel/panel/mock_tests', [AdminPanelController::class, 'mock_tests'])->name('mock_tests');
Route::get('/adminpanel/panel/deleteMockTestItem', [AdminPanelController::class, 'deleteMockTestItem'])->name('deleteMockTestItem');
Route::post('/adminpanel/panel/addMockTestItem', [AdminPanelController::class, 'addMockTestItem'])->name('addMockTestItem');
Route::post('/adminpanel/panel/editMockTestItem', [AdminPanelController::class, 'editMockTestItem'])->name('editMockTestItem');
Route::post('/adminpanel/panel/searchMockTestItem', [AdminPanelController::class, 'searchMockTestItem'])->name('searchMockTestItem');

Route::get('/adminpanel/panel/test_questions', [AdminPanelController::class, 'test_questions'])->name('test_questions');
Route::get('/adminpanel/panel/deleteTestQuestionsItem', [AdminPanelController::class, 'deleteTestQuestionsItem'])->name('deleteTestQuestionsItem');
Route::post('/adminpanel/panel/addTestQuestionsItem', [AdminPanelController::class, 'addTestQuestionsItem'])->name('addTestQuestionsItem');
Route::post('/adminpanel/panel/editTestQuestionsItem', [AdminPanelController::class, 'editTestQuestionsItem'])->name('editTestQuestionsItem');
Route::post('/adminpanel/panel/searchTestQuestionsItem', [AdminPanelController::class, 'searchTestQuestionsItem'])->name('searchTestQuestionsItem');

Route::get('/adminpanel/panel/pdf_notes', [AdminPanelController::class, 'pdf_notes'])->name('pdf_notes');
Route::get('/adminpanel/panel/deletePdfNotesItem', [AdminPanelController::class, 'deletePdfNotesItem'])->name('deletePdfNotesItem');
Route::post('/adminpanel/panel/addPdfNotesItem', [AdminPanelController::class, 'addPdfNotesItem'])->name('addPdfNotesItem');
Route::post('/adminpanel/panel/editPdfNotesItem', [AdminPanelController::class, 'editPdfNotesItem'])->name('editPdfNotesItem');

Route::get('/adminpanel/panel/coupan_codes', [AdminPanelController::class, 'coupan_codes'])->name('coupan_codes');
Route::get('/adminpanel/panel/deleteCoupanCodesItem', [AdminPanelController::class, 'deleteCoupanCodesItem'])->name('deleteCoupanCodesItem');
Route::post('/adminpanel/panel/addCoupanCodesItem', [AdminPanelController::class, 'addCoupanCodesItem'])->name('addCoupanCodesItem');
Route::post('/adminpanel/panel/editCoupanCodesItem', [AdminPanelController::class, 'editCoupanCodesItem'])->name('editCoupanCodesItem');

Route::get('/adminpanel/panel/user_orders', [AdminPanelController::class, 'user_orders'])->name('user_orders');
Route::get('/adminpanel/panel/deleteUserOrdersItem', [AdminPanelController::class, 'deleteUserOrdersItem'])->name('deleteUserOrdersItem');
Route::post('/adminpanel/panel/searchUserOrderItem', [AdminPanelController::class, 'searchUserOrderItem'])->name('searchUserOrderItem');

Route::get('/adminpanel/panel/manual_payment_requests', [AdminPanelController::class, 'manual_payment_requests'])->name('manual_payment_requests');
Route::get('/adminpanel/panel/deleteManualRequestsItem', [AdminPanelController::class, 'deleteManualRequestsItem'])->name('deleteManualRequestsItem');
Route::post('/adminpanel/panel/editManualRequestsItem', [AdminPanelController::class, 'editManualRequestsItem'])->name('editManualRequestsItem');

Route::get('/adminpanel/panel/notification', [AdminPanelController::class, 'notification'])->name('notification');
Route::post('/adminpanel/panel/sendNotification', [AdminPanelController::class, 'sendNotification'])->name('sendNotification');

Route::get('/adminpanel/panel/app_settings', [AdminPanelController::class, 'app_settings'])->name('app_settings');
Route::post('/adminpanel/panel/saveSettings', [AdminPanelController::class, 'saveSettings'])->name('saveSettings');

Route::get('/adminpanel/panel/web_admins', [AdminPanelController::class, 'web_admins'])->name('web_admins');
Route::get('/adminpanel/panel/deleteWebAdmin', [AdminPanelController::class, 'deleteWebAdmin'])->name('deleteWebAdmin');
Route::post('/adminpanel/panel/addWebAdminItem', [AdminPanelController::class, 'addWebAdminItem'])->name('addWebAdminItem');
Route::post('/adminpanel/panel/changeAdminPassword', [AdminPanelController::class, 'changeAdminPassword'])->name('changeAdminPassword');

Route::get('/adminpanel/panel/logout', [AdminPanelController::class, 'logout'])->name('logout');

