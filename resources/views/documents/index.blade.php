@extends('layouts.app')

@section('content')
    <style>
        /* ===== Documents Index Premium Animations ===== */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-scale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes shimmer-slide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }
        @keyframes float-gentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes count-glow {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        .animate-fade-in-up { animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .animate-fade-in-scale { animation: fade-in-scale 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .animate-gradient { animation: gradient-shift 8s ease infinite; background-size: 200% 200%; }

        .delay-1 { animation-delay: 0.06s; }
        .delay-2 { animation-delay: 0.12s; }
        .delay-3 { animation-delay: 0.18s; }
        .delay-4 { animation-delay: 0.24s; }

        /* Decorative orb */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.15;
            pointer-events: none;
        }

        /* Document row hover effect */
        .doc-row {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .doc-row td:first-child {
            position: relative;
        }
        .doc-row td:first-child::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 3px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, #6366f1, #06b6d4);
            opacity: 0;
            transform: scaleY(0);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .doc-row:hover {
            background: linear-gradient(90deg, #f8fafc 0%, #eef2ff 40%, #f0f9ff 100%);
        }
        .doc-row:hover td:first-child::before {
            opacity: 1;
            transform: scaleY(1);
        }

        /* Shimmer effect on card */
        .shimmer-card {
            position: relative;
            overflow: hidden;
        }
        .shimmer-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .shimmer-card:hover::after {
            animation: shimmer-slide 0.8s ease forwards;
            opacity: 1;
        }

        /* Search focus glow */
        .search-glow:focus-within {
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15), 0 1px 3px rgba(0,0,0,0.08);
        }

        /* Tab pill active glow */
        .tab-active {
            box-shadow: 0 1px 6px rgba(99,102,241,0.15), 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Status badge pulse */
        .status-pulse {
            position: relative;
        }
        .status-pulse::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 9999px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .doc-row:hover .status-pulse::after {
            animation: count-glow 1.5s ease infinite;
        }

        /* Action button */
        .action-btn {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }

        /* Custom scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 50%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Pagination Premium Styling */
        /* Hide Laravel's default "Showing X to Y of Z results" text - we have our own */
        .pagination-premium nav > div:first-child { display: none; }
        .pagination-premium nav > div.hidden { display: flex !important; }
        /* Hide the default text info inside the nav */
        .pagination-premium nav > div > div:first-child { display: none; }
        /* The button container */
        .pagination-premium nav > div > div:last-child > span {
            display: inline-flex;
            gap: 4px;
            border-radius: 1rem;
            box-shadow: none;
            background: transparent;
            align-items: center;
        }
        /* All pagination items (links, spans, current) */
        .pagination-premium nav span > span > span,
        .pagination-premium nav span > a,
        .pagination-premium nav span > span[aria-disabled] > span,
        .pagination-premium nav span > span[aria-current] > span {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px !important;
            margin: 0 !important;
            border-radius: 10px !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            line-height: 1 !important;
            border: 1px solid #e2e8f0 !important;
            background: white !important;
            color: #475569 !important;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
            text-decoration: none !important;
        }
        /* Hover state */
        .pagination-premium nav span > a:hover {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff) !important;
            border-color: #a5b4fc !important;
            color: #4f46e5 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99,102,241,0.15) !important;
        }
        /* Active / Current page */
        .pagination-premium nav span > span[aria-current] > span {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: white !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 4px 14px rgba(99,102,241,0.35) !important;
            cursor: default;
        }
        /* Disabled state (prev/next when on first/last page) */
        .pagination-premium nav span > span[aria-disabled] > span {
            opacity: 0.4 !important;
            cursor: not-allowed !important;
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            box-shadow: none !important;
        }
        /* Three dots separator */
        .pagination-premium nav span > span[aria-disabled]:not([aria-label]) > span {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            min-width: 28px;
            opacity: 0.6 !important;
            color: #94a3b8 !important;
            font-size: 0.9rem !important;
            letter-spacing: 2px;
        }
        /* SVG arrows */
        .pagination-premium svg {
            width: 16px;
            height: 16px;
        }
        /* Mobile pagination */
        .pagination-premium nav > div:first-child { display: none !important; }
        .pagination-premium nav > div.hidden.sm\:flex-1 {
            display: flex !important;
            justify-content: center;
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-fade-in-up, .animate-fade-in-scale { animation: none !important; }
            .doc-row:hover { transform: none; }
            .shimmer-card::after { display: none; }
        }
    </style>

    <div class="w-full max-w-[1600px] mx-auto pb-12">

        {{-- ============================================================ --}}
        {{-- 1. HERO HEADER BANNER --}}
        {{-- ============================================================ --}}
        <div class="animate-fade-in-up mb-6">
            <div class="relative rounded-3xl overflow-hidden shadow-xl shadow-indigo-100/50 border border-white/60">

                {{-- Gradient Background --}}
                @if($tab == 'outbox')
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-400 animate-gradient"></div>
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 via-blue-500 to-cyan-400 animate-gradient"></div>
                @endif

                {{-- Decorative Orbs --}}
                <div class="orb w-[350px] h-[350px] bg-purple-400 -top-28 -right-16" style="opacity:0.2;"></div>
                <div class="orb w-[250px] h-[250px] bg-cyan-300 -bottom-16 -left-12" style="opacity:0.2;"></div>
                <div class="orb w-[150px] h-[150px] bg-pink-400 top-8 right-1/4" style="opacity:0.1;"></div>

                {{-- Pattern overlay --}}
                <div class="absolute inset-0 opacity-[0.06]"
                    style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

                {{-- Content --}}
                <div class="relative z-10 px-6 md:px-10 py-7 md:py-9 flex flex-col md:flex-row items-center justify-between gap-5">

                    {{-- Left: Title --}}
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 mb-4 shadow-sm">
                            @if($tab == 'outbox')
                                <i class="fa-solid fa-paper-plane text-white/80 text-xs"></i>
                            @else
                                <i class="fa-solid fa-inbox text-white/80 text-xs"></i>
                            @endif
                            <span class="text-xs font-semibold text-white/90 tracking-wide">
                                {{ $tab == 'outbox' ? 'Outgoing Documents' : 'Incoming Documents' }}
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2 leading-tight drop-shadow-sm">
                            @if($tab == 'outbox')
                                <i class="fa-solid fa-paper-plane mr-2 text-white/70"></i>หนังสือออก
                            @else
                                <i class="fa-solid fa-inbox mr-2 text-white/70"></i>หนังสือเข้า
                            @endif
                        </h1>
                        <p class="text-white/70 text-sm md:text-base font-medium">
                            @if($tab == 'outbox')
                                เอกสารที่ส่งจาก "หน่วยงานของคุณ"
                            @else
                                เอกสารที่ส่งถึง "หน่วยงานของคุณ"
                            @endif
                        </p>

                        {{-- Document count badge --}}
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 backdrop-blur-sm border border-white/20">
                                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                                    <i class="fa-solid fa-file-lines text-white text-xs"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] text-white/60 font-semibold uppercase">เอกสารทั้งหมด</p>
                                    <p class="text-lg font-bold text-white leading-none tabular-nums">{{ $documents->total() }}</p>
                                </div>
                            </div>
                            @if(request('search'))
                                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-400/20 backdrop-blur-sm border border-amber-300/30">
                                    <div class="w-7 h-7 rounded-lg bg-amber-400/25 flex items-center justify-center">
                                        <i class="fa-solid fa-magnifying-glass text-white text-xs"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] text-white/60 font-semibold uppercase">ค้นหา</p>
                                        <p class="text-sm font-bold text-white leading-none truncate max-w-[120px]">"{{ request('search') }}"</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right: Action Buttons --}}
                    <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('documents.create', ['direction' => 'inbound']) }}"
                            class="action-btn group flex items-center gap-3 px-5 py-3.5 bg-white/95 rounded-2xl shadow-xl shadow-black/5 hover:shadow-2xl cursor-pointer backdrop-blur-sm border border-white/60">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-300/40 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <i class="fa-solid fa-inbox text-sm"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ลงทะเบียน</span>
                                <span class="block text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors leading-tight">หนังสือเข้า</span>
                            </div>
                        </a>
                        <a href="{{ route('documents.create', ['direction' => 'outbound']) }}"
                            class="action-btn group flex items-center gap-3 px-5 py-3.5 bg-white rounded-2xl shadow-xl shadow-black/5 hover:shadow-2xl cursor-pointer backdrop-blur-sm border border-white/60">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-indigo-300/40 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ลงทะเบียน</span>
                                <span class="block text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight">หนังสือออก</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 2. MAIN CONTENT CARD --}}
        {{-- ============================================================ --}}
        <div class="animate-fade-in-up delay-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- Toolbar: Tabs & Search --}}
                <div class="flex flex-col md:flex-row justify-between items-center border-b border-slate-100 bg-gradient-to-r from-slate-50/80 via-white to-indigo-50/30 p-4 md:p-5 gap-4">

                    {{-- Modern Segmented Tabs --}}
                    <div class="flex p-1.5 bg-slate-100/80 rounded-2xl w-full md:w-auto shadow-inner">
                        <a href="{{ route('documents.index', ['tab' => 'inbox']) }}"
                            class="flex-1 md:flex-none px-7 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2.5
                           {{ $tab == 'inbox' ? 'bg-white text-slate-800 shadow-md tab-active ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50' }}">
                            <div class="w-7 h-7 rounded-lg {{ $tab == 'inbox' ? 'bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-sm shadow-indigo-200/50' : 'bg-slate-200/70 text-slate-400' }} flex items-center justify-center transition-all duration-300">
                                <i class="fa-solid fa-inbox text-xs"></i>
                            </div>
                            หนังสือเข้า
                        </a>
                        <a href="{{ route('documents.index', ['tab' => 'outbox']) }}"
                            class="flex-1 md:flex-none px-7 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2.5
                           {{ $tab == 'outbox' ? 'bg-white text-slate-800 shadow-md tab-active ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50' }}">
                            <div class="w-7 h-7 rounded-lg {{ $tab == 'outbox' ? 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm shadow-emerald-200/50' : 'bg-slate-200/70 text-slate-400' }} flex items-center justify-center transition-all duration-300">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                            </div>
                            หนังสือออก
                        </a>
                    </div>

                    {{-- Enhanced Search Box --}}
                    <form action="{{ route('documents.index') }}" method="GET" class="w-full md:w-auto">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="relative search-glow rounded-xl transition-all duration-300">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 block w-full md:w-80 pl-11 pr-10 py-2.5 transition-all shadow-sm hover:border-slate-300 placeholder-slate-400"
                                placeholder="ค้นหาเลขที่, เรื่อง...">
                            @if(request('search'))
                                <a href="{{ route('documents.index', ['tab' => $tab]) }}"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </a>
                            @else
                                <button type="submit"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-indigo-600 transition-colors">
                                    <i class="fa-solid fa-arrow-right text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- ============================================================ --}}
                {{-- 3. DATA TABLE --}}
                {{-- ============================================================ --}}
                <div class="overflow-x-auto custom-scroll">
                    <table class="w-full text-sm text-left" aria-label="รายการเอกสาร">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th scope="col" class="px-6 py-4 text-[11px] text-slate-400 font-semibold uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-hashtag text-[9px] text-slate-300"></i>
                                        เลขที่ / วันที่
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-[11px] text-slate-400 font-semibold uppercase tracking-wider whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-file-lines text-[9px] text-slate-300"></i>
                                        เรื่อง
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-[11px] text-slate-400 font-semibold uppercase tracking-wider whitespace-nowrap" style="width: 14%;">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-tag text-[9px] text-slate-300"></i>
                                        ประเภท / ความด่วน
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-[11px] text-slate-400 font-semibold uppercase tracking-wider whitespace-nowrap" style="width: 16%;">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-user text-[9px] text-slate-300"></i>
                                        ผู้ดำเนินการ
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-[11px] text-slate-400 font-semibold uppercase tracking-wider text-right whitespace-nowrap" style="width: 15%;">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <i class="fa-solid fa-signal text-[9px] text-slate-300"></i>
                                        สถานะ
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-4" style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($documents as $index => $doc)
                                <tr class="doc-row group cursor-pointer {{ $doc->status === 'cancelled' ? 'opacity-50' : '' }}"
                                    onclick="window.location='{{ route('documents.show', $doc) }}'">

                                    {{-- Col 1: Document No & Date --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            @php
                                                $receiveNo = null;
                                                if ($tab == 'inbox') {
                                                    $myDeptId = auth()->user()->department_id;
                                                    $receiveRoute = $doc->routes->filter(function ($r) use ($myDeptId) {
                                                        return $r->action == 'receive'
                                                            && $r->receive_no
                                                            && $r->fromUser
                                                            && $r->fromUser->department_id == $myDeptId;
                                                    })->sortByDesc('id')->first();

                                                    if ($receiveRoute) {
                                                        $receiveNo = $receiveRoute->receive_no;
                                                    }
                                                }
                                            @endphp

                                            @if($receiveNo)
                                                <span class="inline-flex items-center gap-1.5 font-mono font-bold text-emerald-600 text-sm group-hover:text-emerald-700 transition-colors">
                                                    <span class="w-5 h-5 rounded-md bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                                                        <i class="fa-solid fa-check text-[8px] text-emerald-500"></i>
                                                    </span>
                                                    รับ-{{ $receiveNo }}
                                                </span>
                                                <span class="text-[10px] text-slate-400 mt-0.5 ml-6.5">
                                                    (เดิม: {{ $doc->document_no }})
                                                </span>
                                            @else
                                                <span class="font-mono font-bold text-sm transition-colors {{ $doc->status === 'cancelled' ? 'text-red-400 line-through' : 'text-slate-700 group-hover:text-indigo-600' }}">
                                                    {{ $doc->document_no }}
                                                </span>
                                            @endif

                                            <span class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1.5">
                                                <i class="fa-regular fa-calendar text-[9px] text-slate-300"></i>
                                                {{ \Carbon\Carbon::parse($doc->document_date)->toThaiDate() }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Col 2: Title & Attachments --}}
                                    <td class="px-6 py-4">
                                        @if($doc->urgency->name !== 'ปกติ')
                                            <div class="mb-1.5">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold border shadow-sm"
                                                    style="color: {{ $doc->urgency->color }}; border-color: {{ $doc->urgency->color }}30; background-color: {{ $doc->urgency->color }}08;">
                                                    <i class="fa-solid fa-bolt text-[7px]"></i> {{ $doc->urgency->name }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="font-semibold text-base mb-1.5 line-clamp-2 transition-colors {{ $doc->status === 'cancelled' ? 'text-red-400 line-through' : 'text-slate-800 group-hover:text-indigo-600' }}">
                                            {{ $doc->title }}
                                        </div>
                                        @if($doc->attachments->count() > 0)
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gradient-to-r from-slate-50 to-slate-100 text-slate-500 text-[10px] font-medium border border-slate-200/80 shadow-sm">
                                                <i class="fa-solid fa-paperclip text-[9px] text-indigo-400"></i>
                                                {{ $doc->attachments->count() }} ไฟล์แนบ
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Col 3: Type --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col items-start gap-2">
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold bg-gradient-to-r from-slate-50 to-white border border-slate-200/80 text-slate-600 shadow-sm">
                                                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-indigo-400 to-blue-500 shadow-sm"></span>
                                                {{ $doc->type->name }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Col 4: User Avatar --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200/80 shadow-sm overflow-hidden group-hover:shadow-md group-hover:border-indigo-200/60 transition-all duration-300">
                                                    @if($doc->user->avatar)
                                                        <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}"
                                                            alt="{{ $doc->user->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="bg-gradient-to-br from-indigo-500 to-blue-600 w-full h-full flex items-center justify-center text-white text-xs font-bold">
                                                            {{ substr($doc->user->name, 0, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                {{-- Online indicator --}}
                                                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-white flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">{{ $doc->user->name }}</span>
                                                <span class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                                    <i class="fa-solid fa-building text-[7px] text-slate-300"></i>
                                                    {{ $doc->department->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Col 5: Status Badge --}}
                                    <td class="px-6 py-4 text-right">
                                        @php
                                            $effectiveStatus = $doc->status;

                                            if ($tab == 'outbox' && $doc->status === 'active') {
                                                $sendRoutes = $doc->routes->where('action', 'send');
                                                $receiveRoutes = $doc->routes->where('action', 'receive');

                                                if ($sendRoutes->isNotEmpty()) {
                                                    $sentToUsers = $sendRoutes->whereNotNull('to_user_id')->pluck('to_user_id')->unique();
                                                    $sentToDepts = $sendRoutes->whereNotNull('to_department_id')->pluck('to_department_id')->unique();

                                                    $receivedByUsers = $receiveRoutes->pluck('from_user_id')->unique();
                                                    $receivedByDepts = $receiveRoutes->map(fn($r) => $r->fromUser?->department_id)->filter()->unique();

                                                    $allUsersReceived = $sentToUsers->isEmpty() || $sentToUsers->diff($receivedByUsers)->isEmpty();
                                                    $allDeptsReceived = $sentToDepts->isEmpty() || $sentToDepts->diff($receivedByDepts)->isEmpty();

                                                    if ($allUsersReceived && $allDeptsReceived) {
                                                        $effectiveStatus = 'closed';
                                                    }
                                                }
                                            }

                                            $statusConfig = match ($effectiveStatus) {
                                                'draft' => [
                                                    'class' => 'bg-slate-50 text-slate-500 border-slate-200',
                                                    'icon' => 'fa-solid fa-file-pen',
                                                    'text' => 'ฉบับร่าง',
                                                    'dot' => 'bg-slate-400',
                                                    'glow' => '',
                                                ],
                                                'active' => [
                                                    'class' => 'bg-gradient-to-r from-indigo-50 to-blue-50 text-indigo-600 border-indigo-200/80',
                                                    'icon' => '',
                                                    'text' => 'กำลังดำเนินการ',
                                                    'dot' => 'bg-indigo-500',
                                                    'glow' => 'animate-pulse',
                                                ],
                                                'closed' => [
                                                    'class' => 'bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-600 border-emerald-200/80',
                                                    'icon' => 'fa-solid fa-circle-check',
                                                    'text' => 'ดำเนินการเสร็จสิ้น',
                                                    'dot' => 'bg-emerald-500',
                                                    'glow' => '',
                                                ],
                                                'cancelled' => [
                                                    'class' => 'bg-gradient-to-r from-red-50 to-rose-50 text-red-500 border-red-200/80',
                                                    'icon' => 'fa-solid fa-ban',
                                                    'text' => 'ยกเลิกการส่ง',
                                                    'dot' => 'bg-red-500',
                                                    'glow' => '',
                                                ],
                                                default => [
                                                    'class' => 'bg-slate-50 text-slate-500 border-slate-200',
                                                    'icon' => '',
                                                    'text' => $effectiveStatus,
                                                    'dot' => 'bg-slate-400',
                                                    'glow' => '',
                                                ]
                                            };
                                        @endphp
                                        <span class="status-pulse inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border shadow-sm {{ $statusConfig['class'] }}">
                                            @if($statusConfig['icon'])
                                                <i class="{{ $statusConfig['icon'] }} text-[10px]"></i>
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }} {{ $statusConfig['glow'] }}"></span>
                                            @endif
                                            {{ $statusConfig['text'] }}
                                        </span>
                                    </td>

                                    {{-- Col 6: Actions --}}
                                    <td class="px-4 py-4 text-center">
                                        @if($tab == 'outbox')
                                            @php
                                                $canCancelSendInList = $doc->status === 'active'
                                                    && $doc->user_id === auth()->id()
                                                    && !$doc->routes->where('action', 'receive')
                                                        ->where('from_user_id', '!=', auth()->id())->count()
                                                    && !$doc->routes->where('action', 'comment')
                                                        ->where('from_user_id', '!=', auth()->id())->count();
                                            @endphp
                                            @if($canCancelSendInList)
                                                <form action="{{ route('documents.cancelSend', $doc) }}" method="POST"
                                                    onclick="event.stopPropagation()"
                                                    onsubmit="return confirm('ยกเลิกการส่งเอกสาร {{ $doc->document_no }} ?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 hover:border-red-300 hover:shadow-md hover:shadow-red-100/50 transition-all duration-200"
                                                        title="ยกเลิกการส่ง">
                                                        <i class="fa-solid fa-rotate-left text-[10px]"></i> ยกเลิก
                                                    </button>
                                                </form>
                                            @else
                                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-all duration-300">
                                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-all duration-300">
                                                <i class="fa-solid fa-chevron-right text-xs"></i>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="flex flex-col items-center justify-center py-20 text-center">
                                            {{-- Decorative illustration --}}
                                            <div class="relative mb-6">
                                                <div class="w-24 h-24 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl flex items-center justify-center shadow-lg shadow-indigo-50/50 border border-indigo-100/50">
                                                    <i class="fa-regular fa-folder-open text-4xl text-indigo-300"></i>
                                                </div>
                                                {{-- Floating elements --}}
                                                <div class="absolute -top-3 -right-3 w-8 h-8 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl flex items-center justify-center border border-amber-200/50 shadow-sm" style="animation: float-gentle 3s ease-in-out infinite;">
                                                    <i class="fa-solid fa-magnifying-glass text-xs text-amber-400"></i>
                                                </div>
                                                <div class="absolute -bottom-2 -left-2 w-7 h-7 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-lg flex items-center justify-center border border-emerald-200/50 shadow-sm" style="animation: float-gentle 3s ease-in-out infinite 1s;">
                                                    <i class="fa-solid fa-file text-[10px] text-emerald-400"></i>
                                                </div>
                                            </div>
                                            <h3 class="text-slate-800 font-bold text-lg mb-1">ไม่พบเอกสาร</h3>
                                            <p class="text-slate-400 text-sm max-w-xs mx-auto leading-relaxed">
                                                ยังไม่มีเอกสารในหมวดหมู่นี้ หรือลองค้นหาด้วยคำค้นอื่น
                                            </p>
                                            @if($tab != 'inbox')
                                                <a href="{{ route('documents.create') }}"
                                                    class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-indigo-200/50 hover:shadow-xl hover:shadow-indigo-300/50 hover:-translate-y-0.5 transition-all duration-200">
                                                    <i class="fa-solid fa-plus text-xs"></i> สร้างเอกสารใหม่
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ============================================================ --}}
                {{-- 4. PAGINATION --}}
                {{-- ============================================================ --}}
                @if($documents->hasPages())
                    <div class="px-6 py-5 border-t border-slate-100 bg-gradient-to-r from-slate-50/60 via-white to-indigo-50/20 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                            <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center">
                                <i class="fa-solid fa-list-ol text-[9px] text-slate-400"></i>
                            </div>
                            <span>
                                แสดง
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-white border border-slate-200 font-bold text-slate-600 shadow-sm mx-0.5">{{ $documents->firstItem() }}-{{ $documents->lastItem() }}</span>
                                จาก
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-white border border-slate-200 font-bold text-slate-600 shadow-sm mx-0.5">{{ $documents->total() }}</span>
                                รายการ
                            </span>
                        </div>
                        <div class="pagination-premium">
                            {{ $documents->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Entrance animation stagger script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.doc-row');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 100 + (index * 40));
            });
        });
    </script>
@endsection