@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                รายงานสรุปภาพรวม
            </h1>
            <p class="text-slate-500 text-sm mt-1 pl-[3.25rem]">สถิติการใช้งานและปริมาณเอกสารในระบบสารบรรณอิเล็กทรอนิกส์</p>
        </div>
        <div class="flex items-center gap-3 pl-[3.25rem] md:pl-0">
            <div class="text-right hidden md:block">
                <p class="text-xs text-slate-400">ข้อมูลล่าสุด</p>
                <p class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::now()->addYears(543)->format('d F Y H:i') }}</p>
            </div>
            <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="รีเฟรชข้อมูล">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>

    <!-- 1. Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Documents -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute -right-6 -top-6 text-slate-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                <i class="fa-solid fa-layer-group text-9xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-medium border border-blue-100">
                        ทั้งหมด
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($totalDocuments) }}</h3>
                <p class="text-slate-500 text-sm">เอกสารในระบบ</p>
                <div class="mt-4 flex items-center text-xs text-green-600 font-medium bg-green-50 w-fit px-2 py-1 rounded-md">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> สะสมตั้งแต่เริ่มระบบ
                </div>
            </div>
        </div>

        <!-- Monthly Documents -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute -right-6 -top-6 text-slate-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                <i class="fa-regular fa-calendar text-9xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center text-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-600 text-xs font-medium border border-purple-100">
                        เดือนนี้
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($thisMonthDocuments) }}</h3>
                <p class="text-slate-500 text-sm">เอกสารใหม่</p>
                <div class="mt-4 flex items-center text-xs text-slate-500 bg-slate-50 w-fit px-2 py-1 rounded-md">
                    <i class="fa-regular fa-clock mr-1"></i> ประจำเดือน {{ \Carbon\Carbon::now()->addYears(543)->format('F Y') }}
                </div>
            </div>
        </div>

        <!-- Completed Documents -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute -right-6 -top-6 text-slate-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                <i class="fa-solid fa-check-double text-9xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white flex items-center justify-center text-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-medium border border-emerald-100">
                        เสร็จสิ้น
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ number_format($completedDocuments) }}</h3>
                <p class="text-slate-500 text-sm">ดำเนินการแล้ว</p>
                <div class="mt-4 flex items-center text-xs text-emerald-600 font-medium bg-emerald-50 w-fit px-2 py-1 rounded-md">
                    <i class="fa-solid fa-chart-pie mr-1"></i> {{ $totalDocuments > 0 ? number_format(($completedDocuments/$totalDocuments)*100, 1) : 0 }}% ของทั้งหมด
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 2. Documents by Type -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-primary-500"></i> แยกตามประเภทเอกสาร
                </h3>
            </div>
            <div class="space-y-5 flex-1 overflow-y-auto pr-2 custom-scrollbar">
                @foreach($docsByType as $item)
                @php 
                    $percentage = $totalDocuments > 0 ? ($item->total / $totalDocuments) * 100 : 0;
                @endphp
                <div class="group">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-slate-700 group-hover:text-primary-600 transition-colors">{{ $item->type->name }}</span>
                        <span class="text-slate-500 text-xs bg-slate-100 px-2 py-0.5 rounded-full">{{ $item->total }} ฉบับ</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-400 to-primary-600 h-2.5 rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $percentage }}%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Top Departments -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-500"></i> หน่วยงานที่ส่งหนังสือมากที่สุด
                </h3>
                <span class="text-xs text-slate-400 bg-slate-50 px-2 py-1 rounded">Top 5</span>
            </div>
            <div class="space-y-4">
                @foreach($docsByDept as $index => $item)
                <div class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 group">
                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center font-bold text-lg mr-4 relative">
                        @if($index == 0)
                            <i class="fa-solid fa-medal text-yellow-400 text-3xl drop-shadow-sm"></i>
                            <span class="absolute text-white text-xs font-bold mt-1">1</span>
                        @elseif($index == 1)
                            <i class="fa-solid fa-medal text-slate-300 text-3xl drop-shadow-sm"></i>
                            <span class="absolute text-white text-xs font-bold mt-1">2</span>
                        @elseif($index == 2)
                            <i class="fa-solid fa-medal text-amber-600 text-3xl drop-shadow-sm"></i>
                            <span class="absolute text-white text-xs font-bold mt-1">3</span>
                        @else
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm font-semibold">
                                {{ $index + 1 }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-800 truncate group-hover:text-primary-700 transition-colors">{{ $item->department->name }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">ส่งเอกสารแล้ว</div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-slate-700 group-hover:text-primary-600">{{ $item->total }}</div>
                        <div class="text-[10px] text-slate-400">ฉบับ</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 4. Monthly Trend -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-indigo-500"></i> แนวโน้มปริมาณเอกสาร
            </h3>
            <span class="text-xs text-slate-500">6 เดือนย้อนหลัง</span>
        </div>
        
        @php
            $maxMonth = $monthlyStats->max('total');
            if($maxMonth == 0) $maxMonth = 1; 
        @endphp

        <div class="relative h-64 w-full">
            <!-- Grid Lines -->
            <div class="absolute inset-0 flex flex-col justify-between text-xs text-slate-300 pointer-events-none">
                <div class="border-b border-slate-100 w-full h-0"></div>
                <div class="border-b border-slate-100 w-full h-0"></div>
                <div class="border-b border-slate-100 w-full h-0"></div>
                <div class="border-b border-slate-100 w-full h-0"></div>
                <div class="border-b border-slate-100 w-full h-0"></div>
            </div>

            <!-- Bars -->
            <div class="absolute inset-0 flex items-end justify-around px-2">
                @foreach($monthlyStats as $stat)
                    @php $height = ($stat->total / $maxMonth) * 100; @endphp
                    <div class="w-full max-w-[80px] flex flex-col items-center group relative h-full justify-end">
                        <!-- Tooltip -->
                        <div class="absolute bottom-full mb-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-20">
                            <div class="bg-slate-800 text-white text-xs py-1 px-2 rounded shadow-lg whitespace-nowrap">
                                {{ $stat->total }} ฉบับ
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                            </div>
                        </div>
                        
                        <!-- Bar -->
                        <div class="w-full sm:w-12 bg-indigo-100 hover:bg-indigo-500 rounded-t-lg transition-all duration-300 relative group-hover:shadow-lg group-hover:-translate-y-1" style="height: {{ $height }}%">
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-indigo-500/20 to-transparent rounded-t-lg"></div>
                        </div>
                        
                        <!-- Label -->
                        <div class="text-xs text-slate-400 mt-3 font-medium">{{ \Carbon\Carbon::parse($stat->month)->addYears(543)->format('M y') }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection