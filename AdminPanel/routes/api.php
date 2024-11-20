<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    
    Route::post('/getBannersData', [APIController::class, 'getBannersData'])->name('getBannersData');
    Route::post('/getEbooksData', [APIController::class, 'getEbooksData'])->name('getEbooksData');
    Route::post('/getSyllabusData', [APIController::class, 'getSyllabusData'])->name('getSyllabusData');
    Route::post('/getVideosCategoryData', [APIController::class, 'getVideosCategoryData'])->name('getVideosCategoryData');
    Route::post('/getVideosData', [APIController::class, 'getVideosData'])->name('getVideosData');

    Route::post('/getNotificationsData', [APIController::class, 'getNotificationsData'])->name('getNotificationsData');
    
    Route::post('/getUserProfileData', [APIController::class, 'getUserProfileData'])->name('getUserProfileData');
    Route::post('/saveUserProfileData', [APIController::class, 'saveUserProfileData'])->name('saveUserProfileData');

    Route::post('/getPaidCoursesData', [APIController::class, 'getPaidCoursesData'])->name('getPaidCoursesData');
    Route::post('/getPaidCourse', [APIController::class, 'getPaidCourse'])->name('getPaidCourse');

    Route::post('/getMockTestCategoryData', [APIController::class, 'getMockTestCategoryData'])->name('getMockTestCategoryData');
    Route::post('/getMockTestData', [APIController::class, 'getMockTestData'])->name('getMockTestData');
    Route::post('/getMockTestQuestionsData', [APIController::class, 'getMockTestQuestionsData'])->name('getMockTestQuestionsData');

    Route::post('/getUserOrdersData', [APIController::class, 'getUserOrdersData'])->name('getUserOrdersData');

    Route::post('/getPDFNotesData', [APIController::class, 'getPDFNotesData'])->name('getPDFNotesData');

    Route::post('/getUpcomingLiveVideosData', [APIController::class, 'getUpcomingLiveVideosData'])->name('getUpcomingLiveVideosData');

    Route::post('/getCoupanCodesData', [APIController::class, 'getCoupanCodesData'])->name('getCoupanCodesData');

    Route::post('/createInstamojoPaymentRequest', [APIController::class, 'createInstamojoPaymentRequest'])->name('createInstamojoPaymentRequest');

    Route::post('/createPhonePePaymentRequest', [APIController::class, 'createPhonePePaymentRequest'])->name('createPhonePePaymentRequest');
    Route::post('/checkPhonePePaymentStatus', [APIController::class, 'checkPhonePePaymentStatus'])->name('checkPhonePePaymentStatus');

    Route::post('/createManualPaymentRequest', [APIController::class, 'createManualPaymentRequest'])->name('createManualPaymentRequest');

    Route::post('/purchaseItem', [APIController::class, 'purchaseItem'])->name('purchaseItem');

});

Route::post('/forgotPass', [APIController::class, 'forgotPass'])->name('forgotPass');
Route::post('/registerUser', [APIController::class, 'registerUser'])->name('registerUser');
Route::post('/loginUser', [APIController::class, 'loginUser'])->name('loginUser');
Route::post('/getSettings', [ApiController::class, 'getSettings'])->name('getSettings');

Route::post('/createPaymentCallback', [ApiController::class, 'createPaymentCallback'])->name('createPaymentCallback');
Route::post('/checkPayment', [ApiController::class, 'checkPayment'])->name('checkPayment');

