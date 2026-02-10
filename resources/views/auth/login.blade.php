<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ - e-Saraban</title>
    <!-- Google Fonts: Kanit (หัวข้อ) & Sarabun (เนื้อหา) -->
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 6s ease-in-out infinite 3s;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body class="bg-slate-50 overflow-hidden">

    <div class="min-h-screen flex relative">

        <!-- Background Decor (Mobile/Tablet) -->
        <div class="absolute inset-0 z-0 lg:hidden">
            <div class="absolute top-0 left-0 w-full h-64 bg-brand-600 rounded-b-[3rem]"></div>
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-brand-600/20 to-transparent"></div>
        </div>

        <!-- Left Side: Branding (Desktop) -->
        <div class="hidden lg:flex lg:w-7/12 relative overflow-hidden bg-brand-900 items-center justify-center p-12">
            <!-- Modern Mesh Gradient Background -->
            <div class="absolute inset-0 bg-brand-900">
                <div
                    class="absolute top-0 right-0 w-[800px] h-[800px] bg-brand-500 rounded-full blur-[120px] opacity-30 -mr-20 -mt-20 animate-pulse-slow">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-indigo-500 rounded-full blur-[100px] opacity-30 -ml-10 -mb-10 animate-float">
                </div>
                <div
                    class="absolute top-1/2 left-1/2 w-[500px] h-[500px] bg-sky-500 rounded-full blur-[120px] opacity-20 transform -translate-x-1/2 -translate-y-1/2 animate-float-delayed">
                </div>
            </div>

            <!-- Noise Texture Overlay -->
            <div class="absolute inset-0 opacity-[0.05] pointer-events-none"
                style="filter: contrast(120%) brightness(100%); background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');">
            </div>

            <!-- Content -->
            <div class="relative z-10 text-center max-w-2xl px-6">
                <div
                    class="mb-10 inline-flex items-center justify-center w-28 h-28 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl ring-1 ring-white/10 animate-float">
                    <i class="fa-solid fa-folder-tree text-5xl text-white drop-shadow-lg"></i>
                </div>

                <h1 class="text-5xl font-black text-white mb-6 leading-tight drop-shadow-sm">
                    ระบบสารบรรณอิเล็กทรอนิกส์
                </h1>

                <p class="text-brand-100 text-xl font-light leading-relaxed mb-10">
                    <strong class="font-semibold text-white">e-Saraban</strong> โรงเรียนพลาธิการ กรมพลาธิการทหารเรือ<br>
                    ยกระดับงานเอกสารสู่ความเป็นดิจิทัล รวดเร็ว ปลอดภัย ตรวจสอบได้
                </p>

                <!-- Status Pills -->
                <div class="flex flex-wrap justify-center gap-3">
                    <span
                        class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-white text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-emerald-400"></i> ปลอดภัยสูง
                    </span>
                    <span
                        class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-white text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-yellow-400"></i> รวดเร็ว
                    </span>
                    <span
                        class="px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-white text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-mobile-screen text-sky-400"></i> รองรับทุกอุปกรณ์
                    </span>
                </div>
            </div>

            <!-- Footer Credit -->
            <div class="absolute bottom-6 left-0 w-full text-center">
                <p class="text-brand-200/40 text-xs uppercase">Secure • Modern • Reliable</p>
            </div>
        </div>

        <!-- Right Side: Login Form (All Screens) -->
        <div class="w-full lg:w-5/12 flex items-center justify-center p-4 sm:p-8 lg:p-12 relative z-10">
            <div class="w-full max-w-[420px]">

                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-10 pt-10">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white text-brand-600 text-4xl shadow-xl mb-4">
                        <i class="fa-solid fa-folder-tree"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 drop-shadow-sm">e-Saraban</h2>
                    <p class="text-slate-500 text-sm mt-1">ระบบสารบรรณอิเล็กทรอนิกส์</p>
                </div>

                <!-- Login Card -->
                <div
                    class="bg-white/80 lg:bg-white rounded-3xl shadow-2xl lg:shadow-xl p-8 sm:p-10 border border-white/50 backdrop-blur-xl relative overflow-hidden group">

                    <!-- Decorative Top Border -->
                    <div
                        class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-brand-400 via-brand-500 to-indigo-500">
                    </div>

                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-slate-800 mb-2">ยินดีต้อนรับ</h3>
                        <p class="text-slate-500 text-sm">กรุณาลงชื่อเข้าใช้งานเพื่อเข้าถึงระบบ</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700" for="email">อีเมล /
                                ชื่อผู้ใช้</label>
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within/input:text-brand-500 text-slate-400">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    autofocus
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 block transition-all hover:bg-white"
                                    placeholder="user@example.com">
                            </div>
                            @error('email') <p class="text-red-500 text-xs font-medium flex items-center gap-1"><i
                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <label class="block text-sm font-semibold text-slate-700"
                                    for="password">รหัสผ่าน</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline">
                                        ลืมรหัสผ่าน?
                                    </a>
                                @endif
                            </div>
                            <div class="relative group/input">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within/input:text-brand-500 text-slate-400">
                                    <i class="fa-solid fa-lock-open"></i>
                                </div>
                                <input id="password" type="password" name="password" required
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 block transition-all hover:bg-white"
                                    placeholder="••••••••">
                            </div>
                            @error('password') <p class="text-red-500 text-xs font-medium flex items-center gap-1"><i
                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                        </div>

                        <!-- Remember & Action -->
                        <div class="flex flex-col gap-6 pt-2">
                            <label class="flex items-center cursor-pointer group/check">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="remember" class="peer sr-only">
                                    <div
                                        class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-brand-600 peer-checked:border-brand-600 transition-all">
                                    </div>
                                    <i
                                        class="fa-solid fa-check text-white text-[10px] absolute left-1 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                                <span
                                    class="ml-2 text-sm text-slate-500 group-hover/check:text-slate-700 transition-colors">จดจำฉันไว้ในระบบ</span>
                            </label>

                            <button type="submit"
                                class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-base px-5 py-3.5 shadow-lg shadow-brand-200 hover:shadow-brand-300 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 group/btn">
                                <span>เข้าสู่ระบบ</span>
                                <i
                                    class="fa-solid fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Info -->
                <div class="mt-10 text-center">
                    <p class="text-slate-400 text-xs">
                        &copy; {{ date('Y') }} กรมพลาธิการทหารเรือ.<br>
                        พัฒนาโดย จ.ท.ธีร์ธวัช พิพัฒน์เดชธน
                    </p>
                    <div
                        class="flex justify-center gap-4 mt-4 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                        <i class="fa-brands fa-chrome text-lg hover:text-blue-500"></i>
                        <i class="fa-brands fa-firefox-browser text-lg hover:text-orange-500"></i>
                        <i class="fa-brands fa-safari text-lg hover:text-sky-500"></i>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>