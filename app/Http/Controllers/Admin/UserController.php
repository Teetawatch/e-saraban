<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        // ดึงข้อมูล User พร้อม Roles และ Department เพื่อลด Query (Eager Loading)
        $users = User::with(['roles', 'department'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        // ส่งข้อมูล Master Data ไปให้หน้า Form เลือก
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_id' => ['required', 'exists:departments,id'],
            'roles' => ['required', 'array'], // ต้องเลือกอย่างน้อย 1 role
        ], [
            'department_id.required' => 'กรุณาระบุหน่วยงานสังกัด',
            'roles.required' => 'กรุณากำหนดสิทธิ์การใช้งาน (Role)',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
        ]);

        // บันทึก Role
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'department_id' => ['required', 'exists:departments,id'],
            'roles' => ['required', 'array'],
            // password เป็น nullable (ถ้าไม่กรอก = ไม่เปลี่ยน)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
        ];

        // ถ้ามีการกรอกรหัสผ่านใหม่ ให้ Hash แล้ว update ด้วย
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'อัปเดตข้อมูลผู้ใช้งานเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // ป้องกันการลบตัวเอง
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีของตนเองขณะล็อกอินได้');
        }

        $user->delete();
        return back()->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }
}