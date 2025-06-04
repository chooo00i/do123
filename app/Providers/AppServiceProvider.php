<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::environment('local')) {
            DB::listen(function ($query) {
                $location = collect(debug_backtrace())->filter(function ($trace) {
                    return !str_contains($trace['file'], 'vendor/');
                })->first(); // vendor/가 아닌 호출 스택의 첫 위치

                $bindings = implode(", ", $query->bindings); // 바인딩 값을 문자열로 변환

                if ($query->time < 1) {
                    return; // 1ms 미만 쿼리는 로그 생략
                }

                Log::channel('queries')->info("
                ------------
                Sql: $query->sql
                Bindings: $bindings
                Time: $query->time
                File: {$location['file']}
                Line: {$location['line']}
                ------------
                ");
            });
        }
    }
}
