@extends('layouts.app')

@section('content')
    <style>
        /* ===== Dashboard Premium Animations ===== */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-scale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes shimmer-line {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 0 20px 4px rgba(99, 102, 241, 0.15); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .animate-fade-in-scale { animation: fade-in-scale 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-gradient { animation: gradient-shift 6s ease infinite; background-size: 200% 200%; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }

        .delay-1 { animation-delay: 0.06s; }
        .delay-2 { animation-delay: 0.12s; }
        .delay-3 { animation-delay: 0.18s; }
        .delay-4 { animation-delay: 0.24s; }
        .delay-5 { animation-delay: 0.30s; }
        .delay-6 { animation-delay: 0.36s; }
        .delay-7 { animation-delay: 0.42s; }
        .delay-8 { animation-delay: 0.48s; }

        /* Stat card with colored glow on hover */
        .stat-glow:hover {
            transform: translateY(-4px);
        }
        .stat-glow {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 50%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Decorative orb */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.15;
            pointer-events: none;
        }

        /* Action card hover */
        .action-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .action-card:hover {
            transform: translateY(-3px);
        }

        /* Pending doc shimmer */
        .pending-card {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .pending-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s ease;
        }
        .pending-card:hover::before {
            left: 100%;
        }
        .pending-card:hover {
            transform: translateX(4px);
        }

        /* Table row */
        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background: linear-gradient(90deg, #f8fafc, #eef2ff, #f8fafc);
        }

        /* Smooth scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Summary bar segments */
        .bar-segment {
            transition: width 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-fade-in-up, .animate-fade-in-scale, .animate-float { animation: none !important; }
            .stat-glow:hover, .action-card:hover, .pending-card:hover { transform: none; }
            .pending-card::before { display: none; }
        }
    </style>

    <div class="w-full max-w-[1600px] mx-auto pb-12">

        {{-- ============================================================ --}}
        {{-- 1. HERO WELCOME BANNER (Vibrant Gradient) --}}
        {{-- ============================================================ --}}
        <div class="animate-fade-in-up mb-8">
            <div class="relative rounded-3xl overflow-hidden shadow-xl shadow-indigo-100/50 border border-white/60">

                {{-- Gradient Background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 via-blue-500 to-cyan-400 animate-gradient"></div>

                {{-- Decorative Orbs --}}
                <div class="orb w-[400px] h-[400px] bg-purple-400 -top-32 -right-20" style="opacity:0.2;"></div>
                <div class="orb w-[300px] h-[300px] bg-cyan-300 -bottom-20 -left-16" style="opacity:0.2;"></div>
                <div class="orb w-[200px] h-[200px] bg-pink-400 top-10 right-1/3" style="opacity:0.12;"></div>

                {{-- Subtle pattern overlay --}}
                <div class="absolute inset-0 opacity-[0.06]"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

                {{-- Content --}}
                <div class="relative z-10 px-6 md:px-10 py-8 md:py-10 flex flex-col md:flex-row items-center justify-between gap-6">

                    {{-- Left: Welcome Text --}}
                    <div class="text-center md:text-left">
                        {{-- Live clock badge --}}
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 mb-5 shadow-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                            </span>
                            <span id="live-clock" class="text-xs font-semibold text-white/90 tracking-wide tabular-nums">
                                {{ \Carbon\Carbon::now()->setTimezone('Asia/Bangkok')->locale('th')->addYears(543)->translatedFormat('j F Y H:i') }} น.
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 leading-tight drop-shadow-sm">
                            สวัสดีคุณ {{ Auth::user()->name }}
                        </h1>
                        <p class="text-white/75 text-sm md:text-base font-medium">
                            {{ Auth::user()->department->name ?? 'ระบบสารบรรณอิเล็กทรอนิกส์' }} &middot; ขอให้วันนี้เป็นวันที่ยอดเยี่ยม!
                        </p>

                        {{-- Inline Stats (Banner) --}}
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-5">
                            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 backdrop-blur-sm border border-white/20 hover:bg-white/25 transition-all cursor-default">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <i class="fa-solid fa-inbox text-white text-sm"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] text-white/60 font-semibold uppercase">รอรับ</p>
                                    <p class="text-lg font-bold text-white leading-none tabular-nums">{{ $incomingDocuments->count() }}</p>
                                </div>
                            </div>
                            @if($urgentCount > 0)
                            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/25 backdrop-blur-sm border border-red-300/30 hover:bg-red-500/35 transition-all cursor-default">
                                <div class="w-8 h-8 rounded-lg bg-red-400/30 flex items-center justify-center">
                                    <i class="fa-solid fa-bolt text-yellow-200 text-sm"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] text-red-100/80 font-semibold uppercase">ด่วน</p>
                                    <p class="text-lg font-bold text-white leading-none tabular-nums">{{ $urgentCount }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right: Create Button --}}
                    <div class="shrink-0">
                        <a href="{{ route('documents.create') }}"
                            class="group flex items-center gap-4 px-6 py-4 bg-white rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-1 transition-all duration-300 cursor-pointer"
                            aria-label="สร้างหนังสือใหม่">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-300/40 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">เมนูลัด</span>
                                <span class="block text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight">สร้างหนังสือ</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 rounded-2xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-up" role="alert">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-check text-white text-sm"></i>
                </div>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- 2. STATS CARDS (Colorful with gradients) --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-8">

            {{-- Inbox --}}
            <a href="{{ route('documents.index', ['tab' => 'inbox']) }}"
                class="stat-glow group bg-white rounded-2xl p-5 md:p-6 border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 cursor-pointer animate-fade-in-up delay-1"
                aria-label="ดูหนังสือเข้าทั้งหมด {{ number_format($inboxCount) }} รายการ">
                {{-- Decorative gradient corner --}}
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-blue-50 via-blue-50/50 to-transparent rounded-bl-[3rem] rounded-tr-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-center justify-between mb-4 relative">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-lg shadow-lg shadow-blue-200/50 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        เอกสารเข้า
                    </span>
                </div>
                <p class="text-3xl md:text-4xl font-extrabold text-slate-800 tabular-nums mb-1">{{ number_format($inboxCount) }}</p>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">หนังสือเข้า</p>
            </a>

            {{-- Outbox --}}
            <a href="{{ route('documents.index', ['tab' => 'outbox']) }}"
                class="stat-glow group bg-white rounded-2xl p-5 md:p-6 border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-100/50 cursor-pointer animate-fade-in-up delay-2"
                aria-label="ดูหนังสือออกทั้งหมด {{ number_format($outboxCount) }} รายการ">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-50 via-emerald-50/50 to-transparent rounded-bl-[3rem] rounded-tr-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-center justify-between mb-4 relative">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center text-lg shadow-lg shadow-emerald-200/50 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        เอกสารออก
                    </span>
                </div>
                <p class="text-3xl md:text-4xl font-extrabold text-slate-800 tabular-nums mb-1">{{ number_format($outboxCount) }}</p>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">หนังสือออก</p>
            </a>

            {{-- Active --}}
            <div class="stat-glow group bg-white rounded-2xl p-5 md:p-6 border border-slate-100 hover:border-amber-200 hover:shadow-xl hover:shadow-amber-100/50 cursor-default animate-fade-in-up delay-3">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-amber-50 via-amber-50/50 to-transparent rounded-bl-[3rem] rounded-tr-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-center justify-between mb-4 relative">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-lg shadow-lg shadow-amber-200/50 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    @if($activeCount > 0)
                        <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-100 animate-pulse">
                            ดำเนินการ
                        </span>
                    @endif
                </div>
                <p class="text-3xl md:text-4xl font-extrabold text-slate-800 tabular-nums mb-1">{{ number_format($activeCount) }}</p>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">กำลังดำเนินการ</p>
            </div>

            {{-- Urgent --}}
            <div class="stat-glow group bg-white rounded-2xl p-5 md:p-6 border border-slate-100 hover:border-red-200 hover:shadow-xl hover:shadow-red-100/50 cursor-default animate-fade-in-up delay-4 {{ $urgentCount > 0 ? 'animate-pulse-glow' : '' }}">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-red-50 via-red-50/50 to-transparent rounded-bl-[3rem] rounded-tr-2xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-center justify-between mb-4 relative">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white flex items-center justify-center text-lg shadow-lg shadow-red-200/50 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    @if($urgentCount > 0)
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    @endif
                </div>
                <p class="text-3xl md:text-4xl font-extrabold text-slate-800 tabular-nums mb-1">{{ number_format($urgentCount) }}</p>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">ด่วนที่สุด</p>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 3. MAIN CONTENT AREA --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ===== LEFT COLUMN (2/3) ===== --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- ======================================= --}}
                {{-- Pending / Incoming Documents --}}
                {{-- ======================================= --}}
                @if($incomingDocuments->count() > 0)
                    <div class="animate-fade-in-up delay-5">
                        {{-- Section Card --}}
                        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                            {{-- Section Header --}}
                            <div class="px-5 md:px-6 py-4 border-b border-slate-50 flex justify-between items-center bg-gradient-to-r from-red-50/60 via-white to-rose-50/40">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white flex items-center justify-center text-sm shadow-md shadow-red-200/50">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div>
                                        <h2 class="font-bold text-slate-800 text-base leading-tight">รอลงรับเอกสาร</h2>
                                        <p class="text-[11px] text-slate-400">กดลงรับเพื่อยืนยันการรับเอกสาร</p>
                                    </div>
                                </div>
                                <span class="bg-gradient-to-r from-red-500 to-rose-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm shadow-red-200/40 tabular-nums">
                                    {{ $incomingDocuments->count() }} รายการ
                                </span>
                            </div>

                            {{-- Scrollable Document List --}}
                            <div class="max-h-[420px] overflow-y-auto custom-scroll divide-y divide-slate-50" id="pending-docs-list">
                                @foreach($incomingDocuments as $index => $doc)
                                    @php
                                        $uc = match ($doc->urgency->name) {
                                            'ด่วนที่สุด' => ['bar' => 'from-red-500 to-rose-500', 'light' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'badge' => 'bg-red-500'],
                                            'ด่วนมาก' => ['bar' => 'from-orange-500 to-amber-500', 'light' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'badge' => 'bg-orange-500'],
                                            'ด่วน' => ['bar' => 'from-amber-400 to-yellow-500', 'light' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'badge' => 'bg-amber-500'],
                                            default => ['bar' => 'from-blue-500 to-indigo-500', 'light' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'badge' => 'bg-blue-500'],
                                        };
                                    @endphp
                                    <div class="pending-card group relative hover:bg-gradient-to-r hover:from-slate-50/80 hover:to-indigo-50/30 transition-all duration-200" id="pending-doc-{{ $doc->id }}">
                                        {{-- Colored left accent --}}
                                        <div class="absolute left-0 top-2 bottom-2 w-1 rounded-full bg-gradient-to-b {{ $uc['bar'] }}"></div>

                                        <div class="flex items-center gap-3 md:gap-4 px-5 md:px-6 py-3.5 pl-5 md:pl-7">
                                            {{-- Icon --}}
                                            <div class="hidden md:flex shrink-0">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:scale-105 transition-transform duration-200 overflow-hidden shadow-sm">
                                                    @if($doc->user->avatar)
                                                        <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}"
                                                            class="w-full h-full object-cover" alt="{{ $doc->user->name }}">
                                                    @else
                                                        @if($doc->type->name == 'หนังสือภายนอก')
                                                            <i class="fa-solid fa-cloud-arrow-down text-sky-500 text-sm"></i>
                                                        @else
                                                            <i class="fa-solid fa-file-lines text-indigo-400 text-sm"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $uc['light'] }} {{ $uc['text'] }} {{ $uc['border'] }}">
                                                        <i class="fa-solid fa-bolt text-[7px]"></i>{{ $doc->urgency->name }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400">
                                                        <i class="fa-regular fa-clock text-[8px]"></i>
                                                        {{ $doc->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('documents.show', $doc) }}" class="block group/title">
                                                    <h3 class="font-semibold text-slate-800 text-sm leading-snug truncate group-hover/title:text-indigo-600 transition-colors">
                                                        {{ $doc->title }}
                                                    </h3>
                                                </a>
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5 text-[11px] text-slate-400">
                                                    <span class="font-mono bg-slate-50 px-1 py-0.5 rounded text-[10px]">{{ $doc->document_no }}</span>
                                                    <span class="text-slate-200">•</span>
                                                    <span>จาก <span class="font-medium text-slate-500">{{ $doc->department->name }}</span></span>
                                                </div>
                                            </div>

                                            {{-- Receive Action --}}
                                            <div class="shrink-0 flex items-center gap-2">
                                                <form action="{{ route('documents.process', $doc) }}" method="POST" class="inline receive-form">
                                                    @csrf
                                                    <input type="hidden" name="action" value="receive">
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200/50 hover:shadow-lg hover:shadow-indigo-300/50 hover:-translate-y-0.5 active:scale-[0.97] transition-all duration-200 whitespace-nowrap cursor-pointer"
                                                        aria-label="ลงรับหนังสือ {{ $doc->title }}"
                                                        onclick="return confirm('ยืนยันลงรับเอกสาร:\n{{ $doc->title }}\n\nเลขที่: {{ $doc->document_no }}');">
                                                        <span class="relative flex h-1.5 w-1.5">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
                                                        </span>
                                                        ลงรับ
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Scroll indicator --}}
                            @if($incomingDocuments->count() > 4)
                                <div class="px-5 py-2.5 bg-gradient-to-t from-slate-50/80 to-transparent border-t border-slate-50 text-center" id="scroll-indicator">
                                    <p class="text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-angles-down text-[9px] animate-bounce"></i>
                                        เลื่อนเพื่อดูเพิ่มเติม ({{ $incomingDocuments->count() }} รายการ)
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ======================================= --}}
                {{-- Recent Documents --}}
                {{-- ======================================= --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm animate-fade-in-up delay-6">
                    {{-- Header --}}
                    <div class="px-5 md:px-6 py-4 border-b border-slate-50 flex justify-between items-center bg-gradient-to-r from-slate-50/80 via-white to-indigo-50/30">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-sm shadow-md shadow-indigo-200/40">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <h2 class="font-bold text-slate-800 text-base">รายการล่าสุด</h2>
                        </div>
                        <a href="{{ route('documents.index') }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1.5 cursor-pointer group"
                            aria-label="ดูเอกสารทั้งหมด">
                            ดูทั้งหมด
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto custom-scroll">
                        <table class="w-full text-sm text-left" aria-label="รายการเอกสารล่าสุด">
                            <thead>
                                <tr class="border-b border-slate-50">
                                    <th class="px-5 md:px-6 py-3 text-[11px] text-slate-400 font-semibold uppercase tracking-wider">เลขที่ / เรื่อง</th>
                                    <th class="px-5 md:px-6 py-3 text-[11px] text-slate-400 font-semibold uppercase tracking-wider">สถานะ</th>
                                    <th class="px-5 md:px-6 py-3 text-[11px] text-slate-400 font-semibold uppercase tracking-wider text-right">วันที่</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDocuments as $doc)
                                    @php
                                        $sc = match ($doc->status) {
                                            'draft' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'dot' => 'bg-slate-400', 'label' => 'ฉบับร่าง', 'icon' => 'bg-slate-100 text-slate-400'],
                                            'active' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'dot' => 'bg-indigo-500', 'label' => 'กำลังดำเนินการ', 'icon' => 'bg-indigo-50 text-indigo-500'],
                                            'closed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500', 'label' => 'เสร็จสิ้น', 'icon' => 'bg-emerald-50 text-emerald-500'],
                                            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'dot' => 'bg-slate-400', 'label' => $doc->status, 'icon' => 'bg-slate-100 text-slate-400'],
                                        };
                                    @endphp
                                    <tr class="table-row cursor-pointer border-b border-slate-50/80 last:border-0"
                                        onclick="window.location='{{ route('documents.show', $doc) }}'"
                                        role="link" tabindex="0"
                                        aria-label="ดูเอกสาร {{ $doc->title }}">
                                        <td class="px-5 md:px-6 py-3.5">
                                            <div class="flex items-start gap-3">
                                                <div class="mt-0.5 w-9 h-9 rounded-xl {{ $sc['icon'] }} flex items-center justify-center shrink-0">
                                                    <i class="fa-regular fa-file-lines text-sm"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-semibold text-slate-700 leading-snug line-clamp-1 text-sm">{{ $doc->title }}</div>
                                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $doc->document_no }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc['bg'] }} {{ $sc['text'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                                {{ $sc['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 md:px-6 py-3.5 text-right">
                                            <div class="text-sm text-slate-600 font-medium">{{ \Carbon\Carbon::parse($doc->updated_at)->toThaiDate() }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($doc->updated_at)->diffForHumans() }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center mb-3 shadow-sm">
                                                    <i class="fa-regular fa-folder-open text-xl text-slate-300"></i>
                                                </div>
                                                <p class="text-sm font-semibold text-slate-400">ไม่มีรายการล่าสุด</p>
                                                <p class="text-xs text-slate-300 mt-1">เอกสารใหม่จะแสดงที่นี่</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT COLUMN (1/3) ===== --}}
            <div class="space-y-5">

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm animate-fade-in-up delay-6">
                    <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 text-white flex items-center justify-center text-xs shadow-sm shadow-purple-200/40">
                            <i class="fa-solid fa-grip"></i>
                        </div>
                        เมนูด่วน
                    </h3>
                    <div class="grid grid-cols-2 gap-2.5">
                        {{-- Create --}}
                        <a href="{{ route('documents.create') }}"
                            class="action-card flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-indigo-50/80 to-blue-50/60 border border-indigo-100/60 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100/30 group cursor-pointer"
                            aria-label="สร้างเอกสารใหม่">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center mb-2 shadow-md shadow-indigo-200/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-plus text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-700 transition-colors">สร้างหนังสือ</span>
                        </a>

                        {{-- Search --}}
                        <a href="{{ route('documents.index') }}"
                            class="action-card flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-sky-50/80 to-cyan-50/60 border border-sky-100/60 hover:border-sky-200 hover:shadow-lg hover:shadow-sky-100/30 group cursor-pointer"
                            aria-label="ค้นหาเอกสาร">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600 text-white flex items-center justify-center mb-2 shadow-md shadow-sky-200/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-sky-700 transition-colors">ค้นหา</span>
                        </a>

                        {{-- E-Filing --}}
                        <a href="{{ route('folders.index') }}"
                            class="action-card flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-violet-50/80 to-purple-50/60 border border-violet-100/60 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-100/30 group cursor-pointer"
                            aria-label="ตู้เอกสารออนไลน์">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 text-white flex items-center justify-center mb-2 shadow-md shadow-violet-200/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-folder-tree text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-violet-700 transition-colors">ตู้เอกสาร</span>
                        </a>

                        {{-- Profile --}}
                        <a href="{{ route('profile.edit') }}"
                            class="action-card flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-pink-50/80 to-rose-50/60 border border-pink-100/60 hover:border-pink-200 hover:shadow-lg hover:shadow-pink-100/30 group cursor-pointer"
                            aria-label="แก้ไขข้อมูลส่วนตัว">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 text-white flex items-center justify-center mb-2 shadow-md shadow-pink-200/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-user-gear text-sm"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-pink-700 transition-colors">ข้อมูลส่วนตัว</span>
                        </a>

                        @if(Auth::user()->can('access-admin'))
                            {{-- Reports --}}
                            <a href="{{ route('admin.reports.index') }}"
                                class="action-card flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-orange-50/80 to-amber-50/60 border border-orange-100/60 hover:border-orange-200 hover:shadow-lg hover:shadow-orange-100/30 group cursor-pointer"
                                aria-label="ดูรายงานสรุป">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 text-white flex items-center justify-center mb-2 shadow-md shadow-orange-200/50 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-chart-pie text-sm"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-600 group-hover:text-orange-700 transition-colors">รายงาน</span>
                            </a>

                            {{-- Users --}}
                            <a href="{{ route('admin.users.index') }}"
                                class="action-card flex flex-col items-center justify-center p-4 rounded-xl bg-gradient-to-br from-teal-50/80 to-emerald-50/60 border border-teal-100/60 hover:border-teal-200 hover:shadow-lg hover:shadow-teal-100/30 group cursor-pointer"
                                aria-label="จัดการผู้ใช้งาน">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center mb-2 shadow-md shadow-teal-200/50 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-users-gear text-sm"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-600 group-hover:text-teal-700 transition-colors">ผู้ใช้งาน</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Summary Overview Card --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm animate-fade-in-up delay-7">
                    <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center text-xs shadow-sm shadow-blue-200/40">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        สรุปภาพรวม
                    </h3>
                    <div class="space-y-2">
                        {{-- Row items --}}
                        @php
                            $summaryItems = [
                                ['label' => 'หนังสือเข้า', 'value' => $inboxCount, 'color' => 'from-blue-500 to-indigo-500', 'dot' => 'bg-blue-500'],
                                ['label' => 'หนังสือออก', 'value' => $outboxCount, 'color' => 'from-emerald-500 to-teal-500', 'dot' => 'bg-emerald-500'],
                                ['label' => 'กำลังดำเนินการ', 'value' => $activeCount, 'color' => 'from-amber-400 to-orange-500', 'dot' => 'bg-amber-500'],
                                ['label' => 'ด่วนที่สุด', 'value' => $urgentCount, 'color' => 'from-red-500 to-rose-500', 'dot' => 'bg-red-500'],
                                ['label' => 'รอลงรับ', 'value' => $incomingDocuments->count(), 'color' => 'from-pink-500 to-rose-500', 'dot' => 'bg-pink-500'],
                            ];
                        @endphp
                        @foreach($summaryItems as $si)
                            <div class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-slate-50/80 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-2.5 h-2.5 rounded-full bg-gradient-to-r {{ $si['color'] }} shadow-sm"></div>
                                    <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">{{ $si['label'] }}</span>
                                </div>
                                <span class="text-sm font-bold text-slate-800 tabular-nums bg-slate-50 px-2.5 py-0.5 rounded-lg group-hover:bg-white group-hover:shadow-sm transition-all">
                                    {{ number_format($si['value']) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bar Chart --}}
                    @php
                        $total = max($inboxCount + $outboxCount, 1);
                        $inboxPct = round(($inboxCount / $total) * 100);
                        $outboxPct = 100 - $inboxPct;
                    @endphp
                    <div class="mt-4 pt-4 border-t border-slate-50">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-3">สัดส่วนเข้า / ออก</p>
                        <div class="flex rounded-full overflow-hidden h-3 bg-slate-100 shadow-inner">
                            <div class="bar-segment bg-gradient-to-r from-blue-500 to-indigo-500 rounded-r-full" style="width: {{ $inboxPct }}%"></div>
                            <div class="bar-segment bg-gradient-to-r from-emerald-400 to-teal-500 rounded-l-full" style="width: {{ $outboxPct }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                                <span class="text-[11px] text-blue-600 font-semibold">เข้า {{ $inboxPct }}%</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                                <span class="text-[11px] text-emerald-600 font-semibold">ออก {{ $outboxPct }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- System Info Card --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500 rounded-2xl p-5 shadow-lg shadow-indigo-200/40 animate-fade-in-up delay-8">
                    {{-- Decorative circles --}}
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-white/10 rounded-full"></div>
                    <div class="absolute top-1/2 right-1/4 w-8 h-8 bg-white/5 rounded-full"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-folder-open text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">ระบบสารบรรณ</p>
                                <p class="text-[11px] text-white/60">อิเล็กทรอนิกส์</p>
                            </div>
                        </div>
                        <p class="text-xs text-white/70 leading-relaxed">
                            ระบบจัดการหนังสือราชการ รับ-ส่ง ติดตามสถานะ และจัดเก็บเอกสารอิเล็กทรอนิกส์อย่างมีประสิทธิภาพ
                        </p>
                        <div class="mt-4 pt-3 border-t border-white/10">
                            <div class="flex items-center gap-2 text-white/50 text-[10px]">
                                <i class="fa-regular fa-copyright"></i>
                                <span>พัฒนาโดย รร.พธ.พธ.ทร.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Live Clock --}}
    <script>
        (function() {
            const clockEl = document.getElementById('live-clock');
            if (!clockEl) return;

            const thaiMonths = [
                'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน',
                'กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'
            ];

            function updateClock() {
                const now = new Date();
                const day = now.getDate();
                const month = thaiMonths[now.getMonth()];
                const year = now.getFullYear() + 543;
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${day} ${month} ${year} ${hours}:${minutes}:${seconds} น.`;
            }

            updateClock();
            setInterval(updateClock, 1000);
        })();

        // Scroll indicator for pending docs
        (function() {
            const list = document.getElementById('pending-docs-list');
            const indicator = document.getElementById('scroll-indicator');
            if (!list || !indicator) return;

            list.addEventListener('scroll', function() {
                const isAtBottom = list.scrollHeight - list.scrollTop - list.clientHeight < 20;
                indicator.style.opacity = isAtBottom ? '0' : '1';
                indicator.style.transition = 'opacity 0.3s ease';
            });
        })();

        // Receive form loading state
        document.querySelectorAll('.receive-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> กำลังลงรับ...';
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                }
            });
        });
    </script>
@endsection