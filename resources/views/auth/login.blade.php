<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ - e-Saraban</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> body { font-family: 'Kanit', sans-serif; } </style>
</head>
<body class="bg-white">

    <div class="min-h-screen flex">
        
        <!-- Left Side: Branding (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-800 to-slate-900 relative overflow-hidden items-center justify-center">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-primary-500 rounded-full blur-3xl"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 text-center px-10">
                <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-2xl">
                    <i class="fa-solid fa-folder-open text-5xl text-white"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4 tracking-wide">ระบบสารบรรณอิเล็กทรอนิกส์</h1>
                <p class="text-slate-300 text-lg font-light leading-relaxed max-w-md mx-auto">
                    โรงเรียนพลาธิการ กรมพลาธิการทหารเรือ<br>เพื่อการบริหารจัดการเอกสารภาครัฐที่ทันสมัย<br>รวดเร็ว ปลอดภัย และตรวจสอบได้
                </p>
                
                <div class="mt-12 flex justify-center gap-4 opacity-50">
                    <i class="fa-brands fa-laravel text-2xl text-white"></i>
                    <i class="fa-brands fa-php text-2xl text-white"></i>
                    <i class="fa-solid fa-shield-halved text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-slate-50 lg:bg-white">
            <div class="w-full max-w-md">
                
                <!-- Mobile Logo (Visible only on Mobile) -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-600 text-white text-3xl shadow-lg mb-4">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">e-Saraban</h2>
                </div>

                <div class="bg-white lg:bg-transparent rounded-2xl shadow-xl lg:shadow-none p-8 lg:p-0 border border-slate-100 lg:border-none">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-slate-800 mb-2">ยินดีต้อนรับกลับ</h2>
                        <p class="text-slate-500">กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2" for="email">อีเมล</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-regular fa-envelope text-slate-400"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block transition-all"
                                    placeholder="name@example.com">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-slate-700" for="password">รหัสผ่าน</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700 hover:underline">
                                        ลืมรหัสผ่าน?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-slate-400"></i>
                                </div>
                                <input id="password" type="password" name="password" required
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block transition-all"
                                    placeholder="••••••••">
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center mb-8">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-primary-600 bg-slate-100 border-slate-300 rounded focus:ring-primary-500 focus:ring-2 cursor-pointer">
                            <label for="remember_me" class="ml-2 text-sm font-medium text-slate-600 cursor-pointer">จดจำฉันไว้ในระบบ</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full text-white bg-primary-700 hover:bg-primary-800 font-semibold rounded-xl text-base px-5 py-3.5 text-center shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                            <span>เข้าสู่ระบบ</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        <!-- Register Link -->
                        {{-- เปิดใช้ส่วนนี้ถ้าต้องการให้สมัครสมาชิกเองได้ --}}
                        {{-- 
                        <div class="mt-8 text-center text-sm text-slate-500">
                            ยังไม่มีบัญชีผู้ใช้? 
                            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700 hover:underline">ลงทะเบียนใหม่</a>
                        </div> 
                        --}}
                    </form>
                </div>
                
                <p class="text-center text-slate-400 text-xs mt-8 lg:mt-12">
                    &copy; {{ date('Y') }} EDMS System. ออกแบบและพัฒนาระบบ จ.ท.ธีร์ธวัช พิพัฒน์เดชธน สงวนลิขสิทธิ์
                </p>
            </div>
        </div>
    </div>
</body>
</html>