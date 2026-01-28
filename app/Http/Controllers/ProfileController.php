<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * แสดงหน้าฟอร์มแก้ไขข้อมูลส่วนตัว
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * อัปเดตข้อมูลทั่วไป (ชื่อ, อีเมล, รูปภาพ)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'max:5120'], // รูปภาพต้องไม่เกิน 5MB
        ]);

        // อัปเดตชื่อและอีเมล
        $user->name = $request->name;
        $user->email = $request->email;

        // จัดการอัปโหลดรูปภาพ
        if ($request->hasFile('avatar')) {
            // ลบรูปเก่าทิ้ง (ถ้ามี)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // บันทึกรูปใหม่ลง storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'บันทึกข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    /**
     * เปลี่ยนรหัสผ่าน
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
}