<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Pagination\Paginator;

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
        // 1. ตั้งค่าให้ Pagination ใช้ Tailwind CSS (ถ้าไม่ใส่ ปุ่มจะเพี้ยนใหญ่ๆ)
        Paginator::useTailwind();

        // 2. สร้าง Gate ชื่อ 'access-admin' เอาไว้เช็คสิทธิ์เข้าเมนู Admin
        Gate::define('access-admin', function (User $user) {
            // เช็คว่า User มี Role ชื่อ 'admin' หรือไม่ (ฟังก์ชัน hasRole อยู่ใน User Model)
            return $user->hasRole('admin'); 
        });

        // 3. Carbon Macros for Thai Date
        \Illuminate\Support\Carbon::macro('toThaiDate', function () {
            return $this->setTimezone('Asia/Bangkok')->locale('th')->addYears(543)->translatedFormat('j F Y');
        });

        \Illuminate\Support\Carbon::macro('toThaiDateTime', function () {
            return $this->setTimezone('Asia/Bangkok')->locale('th')->addYears(543)->translatedFormat('j F Y H:i');
        });

        \Illuminate\Support\Carbon::macro('toThaiTime', function () {
            return $this->setTimezone('Asia/Bangkok')->format('H:i');
        });
    }
}