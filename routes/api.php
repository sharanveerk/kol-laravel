<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;


Route::middleware(['api'])->group(function () {

    Route::post('register',[AuthController::Class,'registration']);
    Route::post('verify-OTP',[AuthController::Class,'verifyOTP']);
    Route::post('resend-OTP',[AuthController::Class,'resendOTP']);
    Route::post('login', [AuthController::class, 'login']);
    Route::patch('check-email-forgot-password',[AuthController::Class,'checkEmailForgotPassword']);
    Route::put('forgot-password',[AuthController::Class,'forgotPassword']);
    // Route::get('user_verification',[AuthController::Class,'user_verification'])->name('user_verification');
   
    Route::get('varifyResetpassword',[ResetPasswordController::Class,'varifyResetpassword'])->name('varifyResetpassword');
    Route::post('changePassword',[ResetPasswordController::Class,'changePassword'])->name('changePassword');
    // Route::post('resetPassword', [ResetPasswordController::class, 'resetPassword']);
    Route::post('resend-verification-email', [AuthController::class, 'sendVerificationEmail']);
    Route::post('logout', [AuthController::class, 'logout']);
  });

  Route::group(['middleware' => ['jwt.verify']], function() {
    
     Route::put('reset-password', [AuthController::class, 'resetPassword']);
  });
  
  Route::group(['middleware' => 'isAdmin'], function () {
    Route::get('view-all-user',[UserController::Class,'displayAllUser']);
    Route::get('view-user-details', [UserController::class, 'getUserDetailsByID']);
    Route::post('update-user-data', [UserController::class, 'updateUserDetails']);
    Route::get('get-user-address', [UserController::class, 'addUserAddress']);
    Route::post('add-user-address', [UserController::class, 'storeUserAddress']);
  });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

