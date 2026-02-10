@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fa-solid fa-magnifying-glass mr-2 text-primary-600"></i>ค้นหาเอกสารขั้นสูง
            </h1>
            <p class="text-slate-500 text-sm mt-1">ค้นหาและกรองข้อมูลเอกสารตามเงื่อนไขที่ต้องการ</p>
        </div>
    </div>

    <div class="space-y-6">
        
        <!-- Filter Section (Horizontal) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 text-lg">
                    <i class="fa-solid fa-filter text-brand-500"></i> ตัวกรองข้อมูล
                </h3>
                @if(request()->anyFilled(['keyword', 'start_date', 'end_date', 'document_type_id', 'urgency_level_id', 'department_id']))
                    <a href="{{ route('documents.search') }}" class="inline-flex items-center gap-1 text-sm text-red-500 hover:text-red-700 font-medium hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                        <i class="fa-solid fa-rotate-left"></i> ล้างค่า
                    </a>
                @endif
            </div>
            
            <form action="{{ route('documents.search') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-12 gap-4">
                    <!-- Keyword (Span 3 on XL) -->
                    <div class="xl:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">คำค้นหา</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 block pl-10 p-2.5 transition-all" 
                                placeholder="ระบุเลขที่ หรือ เรื่อง...">
                        </div>
                    </div>

                    <!-- Date Range (Span 3 on XL -> 1.5 each visually, but effectively 2 slots here? Let's use 2 separate fields) -->
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ตั้งแต่วันที่</label>
                        <div class="relative">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-2.5 transition-all">
                        </div>
                    </div>
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ถึงวันที่</label>
                        <div class="relative">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-2.5 transition-all">
                        </div>
                    </div>

                    <!-- Department (Span 2 on XL) -->
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">หน่วยงาน</label>
                        <select name="department_id" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-2.5 transition-all cursor-pointer">
                            <option value="">ทั้งหมด</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type (Span 1.5 -> round to 2? or 1.5? Grid 12 allows fine grain.) 
                         Remaining: 12 - 3 - 2 - 2 - 2 = 3 slots left.
                         We have Type (1.5), Urgency (1.5).
                    -->
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ประเภท</label>
                        <select name="document_type_id" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-2.5 transition-all cursor-pointer">
                            <option value="">ทั้งหมด</option>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="xl:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ความเร่งด่วน</label>
                        <select name="urgency_level_id" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-2.5 transition-all cursor-pointer">
                            <option value="">ทั้งหมด</option>
                            @foreach($urgencyLevels as $urgency)
                                <option value="{{ $urgency->id }}" {{ request('urgency_level_id') == $urgency->id ? 'selected' : '' }}>{{ $urgency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-slate-100">
                    <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl shadow-md shadow-brand-200 hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 ml-auto">
                        <i class="fa-solid fa-filter"></i> ประมวลผลการค้นหา
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Area (Full Width) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Result Header -->
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-brand-500 shadow-sm">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">ผลการค้นหาพบ <span class="text-brand-600 text-base">{{ number_format($documents->total()) }}</span> รายการ</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold text-slate-600 w-32">เลขที่หนังสือ</th>
                            <th scope="col" class="px-6 py-4 font-bold text-slate-600">เรื่อง</th>
                            <th scope="col" class="px-6 py-4 font-bold text-slate-600">ประเภท</th>
                            <th scope="col" class="px-6 py-4 font-bold text-slate-600">หน่วยงาน</th>
                            <th scope="col" class="px-6 py-4 font-bold text-slate-600 text-right">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($documents as $doc)
                        <tr class="group hover:bg-indigo-50/30 transition-colors cursor-pointer" onclick="window.location='{{ route('documents.show', $doc) }}'">
                            
                            <!-- Col 1: ID & Date -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-mono font-bold text-brand-600 text-sm group-hover:text-brand-700 transition-colors bg-brand-50 px-2 py-0.5 rounded w-fit border border-brand-100">
                                        {{ $doc->document_no }}
                                    </span>
                                    <span class="text-xs text-slate-400 mt-1 pl-1">
                                        <i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($doc->document_date)->addYears(543)->format('d/m/y') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Col 2: Title -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700 line-clamp-2 group-hover:text-brand-700 transition-colors mb-1">{{ $doc->title }}</div>
                                @if($doc->urgency->name !== 'ปกติ')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border shadow-sm" 
                                        style="color: {{ $doc->urgency->color }}; border-color: {{ $doc->urgency->color }}30; background-color: {{ $doc->urgency->color }}10;">
                                        <i class="fa-solid fa-bolt"></i> {{ $doc->urgency->name }}
                                    </span>
                                @endif
                                @if($doc->confidential->name !== 'ปกติ')
                                     <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border border-red-200 bg-red-50 text-red-600 ml-1">
                                        <i class="fa-solid fa-shield-halved"></i> {{ $doc->confidential->name }}
                                    </span>
                                @endif
                            </td>

                            <!-- Col 3: Type -->
                            <td class="px-6 py-4">
                                <span class="text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-lg text-xs font-medium shadow-sm">
                                    {{ $doc->type->name }}
                                </span>
                            </td>

                            <!-- Col 4: Department -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <div class="w-6 h-6 rounded bg-slate-100 flex items-center justify-center text-slate-400 text-xs">
                                        <i class="fa-regular fa-building"></i>
                                    </div>
                                    <span class="text-xs font-medium">{{ $doc->department->name }}</span>
                                </div>
                            </td>

                            <!-- Col 5: Status -->
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border shadow-sm
                                    {{ match($doc->status) {
                                        'draft' => 'bg-slate-50 text-slate-500 border-slate-200',
                                        'active' => 'bg-brand-50 text-brand-600 border-brand-100',
                                        'closed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        default => 'bg-slate-50 text-slate-600'
                                    } }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ match($doc->status) {
                                        'draft' => 'bg-slate-400',
                                        'active' => 'bg-brand-500 animate-pulse',
                                        'closed' => 'bg-emerald-500',
                                        default => 'bg-slate-400'
                                    } }}"></span>
                                    {{ match($doc->status) {
                                        'draft' => 'ฉบับร่าง',
                                        'active' => 'กำลังดำเนินการ',
                                        'closed' => 'เสร็จสิ้น',
                                        default => $doc->status
                                    } }}
                                </span>
                            </td>

                            <!-- Col 6: Arrow -->
                            <td class="px-6 py-4 text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-slate-300 group-hover:text-brand-600 group-hover:bg-brand-50 transition-all">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="flex flex-col items-center justify-center py-20 text-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 border border-slate-100 shadow-inner">
                                        <i class="fa-solid fa-magnifying-glass text-4xl text-slate-300 opacity-50"></i>
                                    </div>
                                    <h3 class="text-slate-800 font-bold text-lg">ไม่พบข้อมูลเอกสาร</h3>
                                    <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                                        ลองปรับเปลี่ยนคำค้นหา หรือช่วงเวลาที่ต้องการค้นหาอีกครั้ง
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($documents->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection