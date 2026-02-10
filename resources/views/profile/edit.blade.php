@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-10 px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fa-solid fa-user-gear mr-2 text-primary-600"></i>ข้อมูลส่วนตัว
            </h1>
            <p class="text-slate-500 text-sm mt-1">จัดการข้อมูลบัญชีผู้ใช้และรหัสผ่านของคุณ</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <i class="fa-solid fa-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        
        <!-- 1. Edit Profile Info Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6 pb-4 border-b border-slate-100">
                ข้อมูลทั่วไป
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Avatar Upload -->
                    <div class="md:col-span-1 flex flex-col items-center">
                        <div class="relative group cursor-pointer mb-4">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-slate-100 shadow-md group-hover:border-primary-100 transition-colors bg-slate-100">
                                <img id="avatar-preview" 
                                     src="{{ $user->avatar ? route('storage.file', ['path' => $user->avatar]) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0ea5e9&color=fff&size=256' }}" 
                                     alt="Avatar" 
                                     class="w-full h-full object-cover">
                            </div>
                            <label for="avatar" class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-primary-700 transition-colors">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <p class="text-xs text-slate-400 text-center">
                            รองรับไฟล์ JPG, PNG, GIF<br>(ขนาดไม่เกิน 5MB)
                        </p>
                    </div>

                    <!-- Input Fields -->
                    <div class="md:col-span-2 space-y-5">
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อ-นามสกุล</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full pl-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 block p-2.5 transition-all" required>
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">อีเมล</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full pl-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 block p-2.5 transition-all" required>
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Readonly Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">หน่วยงาน</label>
                                <div class="bg-slate-100 border border-slate-200 text-slate-600 text-sm rounded-xl p-2.5">
                                    {{ $user->department->name ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">สิทธิ์การใช้งาน</label>
                                <div class="bg-slate-100 border border-slate-200 text-slate-600 text-sm rounded-xl p-2.5">
                                    {{ $user->roles->first()->label ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 text-right">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                บันทึกข้อมูล
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Update Password Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6 pb-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-lock text-slate-400"></i> เปลี่ยนรหัสผ่าน
            </h3>

            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <p class="text-sm text-slate-500">
                            เพื่อความปลอดภัย กรุณาตั้งรหัสผ่านที่รัดกุม ประกอบด้วยตัวอักษร ตัวเลข และสัญลักษณ์
                        </p>
                    </div>

                    <div class="md:col-span-2 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสผ่านปัจจุบัน</label>
                            <input type="password" name="current_password" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 block p-2.5 transition-all" required>
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสผ่านใหม่</label>
                            <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 block p-2.5 transition-all" required>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 block p-2.5 transition-all" required>
                        </div>

                        <div class="pt-2 text-right">
                            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-md transition-all">
                                เปลี่ยนรหัสผ่าน
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection