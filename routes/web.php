<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelLogController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\HabitController;
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
    Route::resource('log', LogController::class)->only(['store']);
    Route::get('home/{log_id?}', [LogController::class, 'index'])->name('home');
    Route::get('/', [LogController::class, 'index'])->name('root');
    
    // Habit
    Route::resource('habit', HabitController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::get('/habit/{habit}/copy', [HabitController::class, 'copy'])->name('habit.copy');
    
    // LevelLog
    // Route::resource('level-log', LevelLogController::class)->only(['update']);
    Route::patch('level-log/check', [LevelLogController::class, 'check'])->name('level_log.check');
    Route::get('/habits/{log_id}/level-logs/{date}', [LevelLogController::class, 'byDate'])->name('level_logs.by_date');
});
