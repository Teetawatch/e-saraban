@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">แก้ไขข้อมูลผู้ใช้งาน</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-slate-700">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-slate-700">อีเมล (Login) <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Department -->
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-medium text-slate-700">สังกัดหน่วยงาน <span class="text-red-500">*</span></label>
                    <select name="department_id" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">-- เลือกหน่วยงาน --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Roles -->
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-medium text-slate-700">สิทธิ์การใช้งาน (Role) <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($roles as $role)
                        <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"
                                {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-slate-900">{{ $role->label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('roles') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 border-t border-slate-100 my-2">
                    <p class="text-xs text-slate-400 mt-2">ปล่อยว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน</p>
                </div>

                <!-- Password -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-slate-700">รหัสผ่านใหม่</label>
                    <input type="password" name="password" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-slate-700">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors">
                    <i class="fa-solid fa-save mr-2"></i> บันทึกการแก้ไข
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-slate-700 bg-white border border-slate-300 focus:ring-4 focus:ring-slate-100 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-slate-50 focus:outline-none transition-colors">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection