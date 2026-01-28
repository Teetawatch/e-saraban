<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // ดึงข้อมูล User พร้อม Roles และ Department เพื่อลด Query (Eager Loading)
        $users = User::with(['roles', 'department'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // ส่งข้อมูล Master Data ไปให้หน้า Form เลือก
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.users.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
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

    public function edit(User $user)
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
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

    public function destroy(User $user)
    {
        // ป้องกันการลบตัวเอง
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีของตนเองขณะล็อกอินได้');
        }

        $user->delete();
        return back()->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }
}