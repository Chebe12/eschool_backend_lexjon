<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', 'App\Http\Controllers\Auth\UserController@login');
Route::post('/user_register', 'App\Http\Controllers\Auth\UserController@user_register');
Route::post('/admin_register', 'App\Http\Controllers\Auth\UserController@admin_register');
Route::post('/student_register', 'App\Http\Controllers\Auth\UserController@student_register');
Route::post('/teacher_register', 'App\Http\Controllers\Auth\UserController@teacher_register');
Route::post('/send_otp', 'App\Http\Controllers\Auth\UserController@sendOTP');
Route::post('/verify_otp', 'App\Http\Controllers\Auth\UserController@verifyOTP');