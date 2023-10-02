<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssignVideosController;
use App\Http\Controllers\UserImagePostController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\AdminPaymentsController;
use App\Http\Controllers\PaymentsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Package Creation
Route::post('/admin/package',[PackagesController::class,'create_package']);

//Get All Packages
Route::get('/admin/get_package',[PackagesController::class,'get_packages']);

//User Registration
Route::post('/register/user',[UserController::class,'register']);
Route::get('/register/getuser',[UserController::class,'getmember']);

//get single member
Route::get('/register/get_single_user/{id}',[UserController::class,'get_single_member']);

//User Login
Route::post('/login/user',[UserController::class,'userlogin']);

//Update Registered User Status
Route::post('/user/status/{id}',[UserController::class,'change_status']);

//Assign Video 
Route::post('/admin/assign_video',[AssignVideosController::class,'create']);

//Get All Videos
Route::get('/data/get_assign_video',[AssignVideosController::class,'get_all_video']);

//Get Single Video
Route::get('/data/get_single_video/{id}',[AssignVideosController::class,'get_single_video']);

//Expire Video Link
Route::post('/admin/expire_video/{id}',[AssignVideosController::class,'change_video_status']);

//Post Multiple Images
Route::post('/user/{user_id}/post_video_images/{video_id}',[UserImagePostController::class,'add_user_image']);

//Get All Screenshots with or without id

Route::get('/admin/get_video_images/{id?}', [UserImagePostController::class, 'get_screenshots_data']);
 

// screenshots data with date filter
Route::get('/admin/get_video_images/{startDate}/{endDate}', [UserImagePostController::class, 'screenshots_with_date']);

// screenshots data with status filter
Route::get('/admin/video_status/{status}', [UserImagePostController::class, 'screenshots_with_status']);

//screenshots with date and status filter
Route::get('/admin/all_video_images/{startDate}/{endDate}/{status}', [UserImagePostController::class, 'get_screenshots']);
  
//Approve or Reject User Post Screenshots
Route::post('/admin/confirmation/{id}',[UserImagePostController::class,'change_image_status']);

//Admin Send Payment To specific User
Route::post('/admin/pay/{id}',[AdminPaymentsController::class,'create_admin_payment']);

// Admin all payments
Route::get('/admin/payments',[AdminPaymentsController::class,'get_admin_payments']);

// User receive payment transactions
Route::get('/admin/payments/{id}',[AdminPaymentsController::class,'get_payments']);

// All pending ,Total payemnts
Route::get('/payments',[PaymentsController::class,'all_payments']);

//Get specific user payment
Route::get('/user_payment/{id}',[PaymentsController::class,'user_payments']);

