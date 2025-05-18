<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // 로그인
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');

    // 계정 생성
    Route::resource('user-account', UserAccountController::class)->only(['create', 'store']);
});

// 로그아웃
Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');


// 로그인 필요
Route::middleware('auth')->group(function () {
    // 홈(습관 기록)
    Route::get('/', [LogController::class, 'index'])->name('home');
});
