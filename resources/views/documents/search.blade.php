@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/50 relative font-sans">
    
    {{-- CSS for this page --}}
    <style>
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-move 8s ease infinite;
        }
        @keyframes gradient-move {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .form-input-premium {
            @apply w-full bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 shadow-sm hover:border-slate-300;
        }
        .form-label-premium {
            @apply block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2;
        }
        
        /* Table Row Styling */
        .doc-row {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .doc-row:hover {
            transform: translateY(-2px);
            background-color: white !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            position: relative;
            z-index: 10;
            border-radius: 12px; /* Visual trick for separated look if spacing allowed, or just let it float */
        }
        /* Left accent bar */
        .doc-row td:first-child {
            position: relative;
        }
        .doc-row td:first-child::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10%; 
            bottom: 10%;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, #6366f1, #06b6d4);
            opacity: 0;
            transform: scaleY(0);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .doc-row:hover td:first-child::before {
            opacity: 1;
            transform: scaleY(1);
        }
    </style>

    {{-- Hero Background --}}
    <div class="absolute inset-x-0 top-0 h-[380px] bg-gradient-to-br from-indigo-700 via-blue-600 to-cyan-500 animate-gradient -z-10 rounded-b-[40px] shadow-2xl shadow-indigo-900/20 overflow-hidden">
        {{-- Pattern Overlay --}}
        <div class="absolute inset-0 opacity-10" 
             style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
        
        {{-- Decorative Orbs --}}
        <div class="absolute top-[-50px] right-[-50px] w-96 h-96 bg-white/10 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-[-20px] left-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-[60px] pointer-events-none"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 text-white relative z-10">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold border border-white/30 flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> ระบบค้นหา
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-bold tracking-tight mb-3 drop-shadow-sm">
                    ค้นหาเอกสารขั้นสูง
                </h1>
                <p class="text-indigo-100 text-lg font-medium opacity-90 max-w-2xl bg-indigo-900/10 backdrop-blur-sm rounded-lg px-2 py-1 -ml-2">
                    ตัวกรองข้อมูลที่ละเอียดแม่นยำ ช่วยให้คุณเข้าถึงเอกสารได้ทันที
                </p>
            </div>
            
             @if(request()->anyFilled(['keyword', 'start_date', 'end_date', 'document_type_id', 'urgency_level_id', 'department_id']))
                <a href="{{ route('documents.search') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/40 rounded-xl text-white font-semibold transition-all hover:scale-105 active:scale-95 shadow-lg group">
                    <i class="fa-solid fa-rotate-left group-hover:-rotate-180 transition-transform duration-500"></i> ล้างค่าการค้นหา
                </a>
            @endif
        </div>

        {{-- Filter Card --}}
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl p-6 md:p-8 mb-10 relative z-20 shadow-2xl shadow-indigo-500/15 border border-white/50">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100/80">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                     <i class="fa-solid fa-sliders text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">ตัวกรองข้อมูล (Filter)</h3>
                    <p class="text-xs text-slate-500">ระบุเงื่อนไขเพื่อกรองผลลัพธ์</p>
                </div>
            </div>

            <form action="{{ route('documents.search') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-12 gap-6">
                    
                    {{-- Keyword --}}
                    <div class="xl:col-span-4">
                        <label class="form-label-premium">คำค้นหา</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-keyboard"></i>
                            </div>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block pl-11 p-3 transition-all shadow-sm hover:border-slate-300" 
                                placeholder="ระบุเลขที่, ชื่อเรื่อง, หรือคำอธิบาย...">
                        </div>
                    </div>

                    {{-- Date Range --}}
                    <div class="xl:col-span-2">
                        <label class="form-label-premium">ตั้งแต่วันที่</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 p-3 transition-all shadow-sm hover:border-slate-300">
                    </div>
                    <div class="xl:col-span-2">
                        <label class="form-label-premium">ถึงวันที่</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 p-3 transition-all shadow-sm hover:border-slate-300">
                    </div>

                    {{-- Department --}}
                    <div class="xl:col-span-4">
                        <label class="form-label-premium">หน่วยงาน</label>
                        <select name="department_id" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 p-3 transition-all shadow-sm cursor-pointer hover:border-slate-300">
                            <option value="">ทั้งหมด</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Row 2 Grid --}}
                    
                    {{-- Type --}}
                    <div class="xl:col-span-4">
                        <label class="form-label-premium">ประเภทเอกสาร</label>
                        <select name="document_type_id" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 p-3 transition-all shadow-sm cursor-pointer hover:border-slate-300">
                            <option value="">ทั้งหมด</option>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Urgency --}}
                    <div class="xl:col-span-4">
                        <label class="form-label-premium">ความเร่งด่วน</label>
                        <select name="urgency_level_id" class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 p-3 transition-all shadow-sm cursor-pointer hover:border-slate-300">
                            <option value="">ทั้งหมด</option>
                            @foreach($urgencyLevels as $urgency)
                                <option value="{{ $urgency->id }}" {{ request('urgency_level_id') == $urgency->id ? 'selected' : '' }}>{{ $urgency->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit Button (Auto width/fill based on space) --}}
                    <div class="xl:col-span-4 flex items-end">
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold p-3 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            ค้นหาเอกสาร
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Results Section --}}
         <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
            {{-- Search Summary Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center backdrop-blur-sm gap-4">
                 <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-indigo-600 shadow-sm animate-fade-in">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-700">ผลการค้นหา</h4>
                        <p class="text-xs text-slate-500">พบข้อมูลจำนวน <span class="font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">{{ number_format($documents->total()) }}</span> รายการ</p>
                    </div>
                 </div>
                 
                 {{-- Optional: Sort controls could go here --}}
            </div>

            {{-- Table --}}
             <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                     <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4 w-[15%]">เลขที่ / วันที่</th>
                            <th scope="col" class="px-6 py-4 w-[35%]">เรื่อง</th>
                            <th scope="col" class="px-6 py-4 w-[15%]">ประเภท</th>
                            <th scope="col" class="px-6 py-4 w-[20%]">หน่วยงาน</th>
                            <th scope="col" class="px-6 py-4 w-[15%] text-right">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($documents as $doc)
                        <tr class="doc-row group cursor-pointer bg-white" onclick="window.location='{{ route('documents.show', $doc) }}'">
                            
                            {{-- Col 1: ID & Date --}}
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    <span class="font-mono font-bold text-indigo-600 text-sm group-hover:text-indigo-700 transition-colors w-fit">
                                        {{ $doc->document_no }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                        {{ \Carbon\Carbon::parse($doc->document_date)->addYears(543)->format('d/m/y') }}
                                    </span>
                                </div>
                            </td>

                            {{-- Col 2: Title --}}
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-700 text-base mb-1.5 line-clamp-2 group-hover:text-indigo-700 transition-colors">
                                    {{ $doc->title }}
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($doc->urgency->name !== 'ปกติ')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border shadow-sm" 
                                            style="color: {{ $doc->urgency->color }}; border-color: {{ $doc->urgency->color }}30; background-color: {{ $doc->urgency->color }}10;">
                                            <i class="fa-solid fa-bolt text-[8px]"></i> {{ $doc->urgency->name }}
                                        </span>
                                    @endif
                                    @if(isset($doc->confidential) && $doc->confidential->name !== 'ปกติ')
                                         <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border border-red-200 bg-red-50 text-red-600">
                                            <i class="fa-solid fa-shield-halved text-[8px]"></i> {{ $doc->confidential->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Col 3: Type --}}
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 text-xs font-semibold shadow-sm group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-all">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mr-2"></span>
                                    {{ $doc->type->name }}
                                </span>
                            </td>

                            {{-- Col 4: Department --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-sm group-hover:bg-indigo-100 group-hover:text-indigo-500 transition-colors">
                                        <i class="fa-regular fa-building"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-800 transition-colors line-clamp-1">
                                        {{ $doc->department->name }}
                                    </span>
                                </div>
                            </td>

                            {{-- Col 5: Status --}}
                            <td class="px-6 py-5 text-right">
                                @php
                                    $statusClass = match($doc->status) {
                                        'draft' => 'bg-slate-100 text-slate-500 border-slate-200',
                                        'active', 'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'closed', 'completed', 'finish' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                        default => 'bg-blue-50 text-blue-600 border-blue-200'
                                    };
                                    $statusDot = match($doc->status) {
                                        'active', 'pending' => 'bg-amber-500 animate-pulse',
                                        'closed', 'completed', 'finish' => 'bg-emerald-500',
                                        'cancelled' => 'bg-red-500',
                                        default => 'bg-blue-500'
                                    };
                                    $statusLabel = match($doc->status) {
                                        'draft' => 'ฉบับร่าง',
                                        'active' => 'กำลังดำเนินการ',
                                        'pending' => 'รอดำเนินการ',
                                        'closed', 'finish' => 'เสร็จสิ้น',
                                        'cancelled' => 'ยกเลิก',
                                        default => $doc->status
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border shadow-sm {{ $statusClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center bg-slate-50/30">
                                <div class="relative">
                                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100 shadow-sm">
                                        <i class="fa-solid fa-magnifying-glass text-4xl text-slate-300 opacity-50"></i>
                                    </div>
                                    {{-- Floating element --}}
                                    <div class="absolute top-0 right-1/2 translate-x-12 -translate-y-2 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center animate-bounce">
                                        <i class="fa-solid fa-question text-indigo-400 text-sm"></i>
                                    </div>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-2">ไม่พบข้อมูลเอกสาร</h3>
                                <p class="text-slate-500 text-sm max-w-sm mx-auto">
                                    กรุณาลองปรับเปลี่ยนคำค้นหา หรือเลือกเงื่อนไขการกรองใหม่อีกครั้ง
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($documents->hasPages())
                <div class="px-6 py-5 border-t border-slate-100 bg-gradient-to-r from-slate-50/50 via-white to-slate-50/50">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection