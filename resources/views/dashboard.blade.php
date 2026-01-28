@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">


    <!-- 1. Welcome Banner -->
    <div class="relative rounded-[2.5rem] p-8 md:p-12 mb-12 overflow-hidden shadow-2xl shadow-indigo-100/60 border border-white/60 isolate min-h-[300px] flex items-center">
        <!-- Sophisticated Mesh Gradient Background (Light Theme) -->
        <div class="absolute inset-0 bg-white">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-blue-50 to-white opacity-100"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-200/20 rounded-full blur-[100px] -mr-32 -mt-32 mix-blend-multiply animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-200/20 rounded-full blur-[100px] -ml-20 -mb-20 mix-blend-multiply animate-pulse-slow" style="animation-delay: 2s;"></div>
            <!-- Grain Texture (Subtle) -->
            <div class="absolute inset-0 opacity-[0.015]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative z-10 w-full flex flex-col lg:flex-row justify-between items-center gap-10">
            
            <!-- Left: Text Content -->
            <div class="text-center lg:text-left max-w-2xl">
                <!-- Glass Badge (Light) -->
                <div class="inline-flex items-center gap-2.5 mb-6 px-4 py-1.5 rounded-full bg-white/60 backdrop-blur-md border border-white/40 shadow-sm ring-1 ring-white/60 group hover:bg-white/80 transition-all cursor-default">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-sm font-bold text-slate-600 tracking-wide">
                        {{ \Carbon\Carbon::now()->setTimezone('Asia/Bangkok')->locale('th')->addYears(543)->translatedFormat('j F Y H:i') }} น.
                    </span>
                </div>

                <h1 class="text-xl md:text-5xl lg:text-5xl font-black text-slate-800 mb-6 leading-tight tracking-tight drop-shadow-sm">
                    สวัสดีคุณ <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">{{ Auth::user()->name }}</span>
                    <span class="block text-xl md:text-xl font-light text-slate-500 mt-3 tracking-normal">ขอให้วันนี้เป็นวันที่ยอดเยี่ยม!</span>
                </h1>

                <!-- Stats in Banner (Light) -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                     <div class="group flex items-center gap-3 px-5 py-2.5 rounded-xl bg-white/60 backdrop-blur border border-white/40 shadow-sm hover:shadow-md hover:bg-white/80 transition-all cursor-default">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-inbox text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">รอรับ</p>
                            <p class="text-xl font-bold text-slate-800 leading-none">{{ $incomingDocuments->count() }}</p>
                        </div>
                     </div>
                     
                     <div class="group flex items-center gap-3 px-5 py-2.5 rounded-xl bg-white/60 backdrop-blur border border-white/40 shadow-sm hover:shadow-md hover:bg-white/80 transition-all cursor-default">
                        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-bolt text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">เร่งด่วน</p>
                            <p class="text-xl font-bold text-slate-800 leading-none">{{ $urgentCount ?? 0 }}</p>
                        </div>
                     </div>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Create Button (Light Theme) -->
                <a href="{{ route('documents.create') }}" class="group relative flex items-center gap-5 p-6 bg-white text-slate-800 rounded-[2rem] shadow-xl shadow-slate-200/50 hover:shadow-slate-200/80 hover:-translate-y-1 transition-all duration-300 min-w-[260px] overflow-hidden border border-white">
                    <div class="absolute inset-0 bg-gradient-to-br from-white via-slate-50 to-indigo-50 opacity-100 group-hover:opacity-90 transition-opacity"></div>
                    <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 ring-4 ring-indigo-50">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="relative flex flex-col items-start z-10">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">เมนูลัด</span>
                        <span class="text-2xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors tracking-tight">สร้างหนังสือ</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const options = { 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false 
            };
            // Convert to Thai Buddhist Year manually if needed, or rely on locale
            // Simple locale string for now
            const timeString = now.toLocaleDateString('th-TH', options);
            document.getElementById('current-time').textContent = timeString + ' น.';
        }
        setInterval(updateTime, 1000);
    </script>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check"></i>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    <!-- 2. Stats Grid (Glassmorphism / Soft UI) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Inbox -->
        <a href="{{ route('documents.index', ['tab' => 'inbox']) }}" class="group relative bg-white rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:border-blue-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    เอกสารเข้า
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($inboxCount) }}</h3>
                <p class="text-slate-500 font-medium">หนังสือเข้า</p>
            </div>
        </a>

        <!-- Outbox -->
        <a href="{{ route('documents.index', ['tab' => 'outbox']) }}" class="group relative bg-white rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:border-emerald-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-2.5 py-1 rounded-full group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    ออกสารออก
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($outboxCount) }}</h3>
                <p class="text-slate-500 font-medium">หนังสือออก</p>
            </div>
        </a>

        <!-- Active -->
        <div class="group relative bg-white rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:border-orange-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="bg-orange-50 text-orange-600 text-xs font-bold px-2.5 py-1 rounded-full group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    รอดำเนินการ
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($activeCount) }}</h3>
                <p class="text-slate-500 font-medium">กำลังดำเนินการ</p>
            </div>
        </div>

        <!-- Urgent -->
        <div class="group relative bg-white rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:border-red-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-fire"></i>
                </div>
                <span class="bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full group-hover:bg-red-600 group-hover:text-white transition-colors">
                    ความเร่งด่วน
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($urgentCount) }}</h3>
                <p class="text-slate-500 font-medium">เรื่องด่วนที่สุด</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Column: Incoming & Recent (2 Cols) -->
        <div class="xl:col-span-2 space-y-8">
            
            <!-- Incoming Documents -->
            @if($incomingDocuments->count() > 0)
            <div class="mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-xl tracking-tight">งานรอการดำเนินการ (เมื่อทุกแผนกลงรับ การแจ้งเตือนจะหายไป)</h3>
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2.5 py-0.5 rounded-full border border-red-200">
                        {{ $incomingDocuments->count() }} รายการ
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($incomingDocuments as $doc)
                    <div class="group relative bg-white rounded-2xl p-4 md:p-5 border border-slate-200 shadow-sm hover:shadow-xl hover:border-primary-200 transition-all duration-300 overflow-hidden">
                        
                        <!-- Urgency Glow & Indicator -->
                        @php
                            $urgencyColor = match($doc->urgency->name) {
                                'ด่วนที่สุด' => 'red',
                                'ด่วนมาก' => 'orange',
                                'ด่วน' => 'amber', // usually yellow/amber
                                default => 'blue'
                            };
                            $colorClass = "bg-{$urgencyColor}-500";
                            $lightBg = "bg-{$urgencyColor}-50";
                            $textColor = "text-{$urgencyColor}-700";
                            $borderColor = "border-{$urgencyColor}-200";
                        @endphp
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $colorClass }}"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-primary-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                        <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-5 pl-2 relative">
                            
                            <!-- Icon/Avatar Section (Hidden on small mobile, visible on MD up) -->
                            <div class="hidden md:flex shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    @if($doc->user->avatar)
                                        <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}" class="w-full h-full object-cover rounded-2xl">
                                    @else
                                        @if($doc->type->name == 'หนังสือภายนอก')
                                            <i class="fa-solid fa-cloud-arrow-down text-xl text-sky-500"></i>
                                        @else
                                            <i class="fa-solid fa-file-lines text-xl text-slate-400"></i>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="flex-1 min-w-0">
                                <!-- Mobile Top Row: Urgency + Date -->
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold border shadow-sm {{ $lightBg }} {{ $textColor }} {{ $borderColor }}">
                                        <i class="fa-solid fa-bolt mr-1"></i>{{ $doc->urgency->name }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-400 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ $doc->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <a href="{{ route('documents.show', $doc) }}" class="block group/link">
                                    <h4 class="font-bold text-slate-800 text-base md:text-lg leading-snug truncate group-hover:text-primary-600 transition-colors">
                                        {{ $doc->title }}
                                    </h4>
                                    <!-- Meta Info -->
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5 text-xs text-slate-500">
                                        <span class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">{{ $doc->document_no }}</span>
                                        <span class="hidden md:inline text-slate-300">|</span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-building-user md:hidden text-slate-400"></i> 
                                            จาก: <span class="font-medium text-slate-700">{{ $doc->department->name }}</span>
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Action Section (Mobile: Full width bottom bar / Desktop: Right side button) -->
                            <div class="flex items-center justify-between gap-3 mt-2 pt-3 border-t border-slate-100 md:mt-0 md:pt-0 md:border-t-0 md:w-auto w-full">
                                
                                <!-- Mobile Sender Info (Left side of action bar) -->
                                <div class="flex items-center gap-2 md:hidden">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 border border-slate-200 overflow-hidden shrink-0">
                                        @if($doc->user->avatar) <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}" class="w-full h-full object-cover"> @else {{ substr($doc->user->name, 0, 1) }} @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700 leading-tight">{{ $doc->user->name }}</span>
                                        <span class="text-[10px] text-slate-400 leading-tight">ผู้ส่ง</span>
                                    </div>
                                </div>

                                <!-- Button -->
                                <a href="{{ route('documents.show', $doc) }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all text-center md:min-w-[140px]">
                                    <span class="relative flex h-2 w-2 mr-1">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                    </span>
                                    ลงรับ
                                </a>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Documents Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="fa-regular fa-clock text-slate-400"></i> รายการล่าสุด
                    </h3>
                    <a href="{{ route('documents.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">ดูทั้งหมด</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold">เลขที่ / เรื่อง</th>
                                <th class="px-6 py-4 font-semibold">สถานะ</th>
                                <th class="px-6 py-4 font-semibold text-right">วันที่</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentDocuments as $doc)
                            <tr class="group hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location='{{ route('documents.show', $doc) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                            <i class="fa-regular fa-file-lines"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors line-clamp-1">{{ $doc->title }}</div>
                                            <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $doc->document_no }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ match($doc->status) { 'draft' => 'bg-slate-100 text-slate-600', 'active' => 'bg-blue-50 text-blue-600', 'closed' => 'bg-emerald-50 text-emerald-600', default => 'bg-slate-100 text-slate-600' } }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ match($doc->status) { 'draft' => 'bg-slate-400', 'active' => 'bg-blue-500', 'closed' => 'bg-emerald-500', default => 'bg-slate-400' } }}"></span>
                                        {{ match($doc->status) { 'draft' => 'ฉบับร่าง', 'active' => 'กำลังดำเนินการ', 'closed' => 'เสร็จสิ้น', default => $doc->status } }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-slate-600 font-medium">{{ \Carbon\Carbon::parse($doc->updated_at)->toThaiDate() }}</div>
                                    <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($doc->updated_at)->diffForHumans() }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-500">ไม่มีรายการล่าสุด</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Tips (1 Col) -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4">เมนูด่วน</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('documents.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 border border-slate-100 hover:bg-blue-50 hover:border-blue-100 hover:text-blue-600 transition-all group">
                        <i class="fa-solid fa-magnifying-glass text-xl mb-2 text-slate-400 group-hover:text-blue-500"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover:text-blue-600">ค้นหา</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 border border-slate-100 hover:bg-purple-50 hover:border-purple-100 hover:text-purple-600 transition-all group">
                        <i class="fa-solid fa-user-gear text-xl mb-2 text-slate-400 group-hover:text-purple-500"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover:text-purple-600">ข้อมูลส่วนตัว</span>
                    </a>
                    @if(Auth::user()->can('access-admin'))
                    <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 border border-slate-100 hover:bg-orange-50 hover:border-orange-100 hover:text-orange-600 transition-all group">
                        <i class="fa-solid fa-chart-pie text-xl mb-2 text-slate-400 group-hover:text-orange-500"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover:text-orange-600">รายงาน</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 border border-slate-100 hover:bg-emerald-50 hover:border-emerald-100 hover:text-emerald-600 transition-all group">
                        <i class="fa-solid fa-users-gear text-xl mb-2 text-slate-400 group-hover:text-emerald-500"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover:text-emerald-600">ผู้ใช้งาน</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection