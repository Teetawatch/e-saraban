@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                <i class="fa-solid fa-magnifying-glass mr-2 text-primary-600"></i>ค้นหาเอกสารขั้นสูง
            </h1>
            <p class="text-slate-500 text-sm mt-1">ค้นหาและกรองข้อมูลเอกสารตามเงื่อนไขที่ต้องการ</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-filter text-primary-500"></i> ตัวกรองข้อมูล
                    </h3>
                    @if(request()->anyFilled(['keyword', 'start_date', 'end_date', 'document_type_id', 'urgency_level_id', 'department_id']))
                        <a href="{{ route('documents.search') }}" class="text-xs text-red-500 hover:text-red-700 font-medium hover:underline transition-colors">
                            ล้างค่า
                        </a>
                    @endif
                </div>
                
                <form action="{{ route('documents.search') }}" method="GET">
                    <div class="space-y-5">
                        <!-- Keyword -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">คำค้นหา</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-search"></i>
                                </div>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 block pl-10 p-2.5 transition-all" 
                                    placeholder="ระบุเลขที่ หรือ เรื่อง...">
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">ตั้งแต่วันที่</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 p-2.5 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">ถึงวันที่</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 p-2.5 transition-all">
                            </div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">ประเภทเอกสาร</label>
                            <select name="document_type_id" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 p-2.5 transition-all cursor-pointer">
                                <option value="">ทั้งหมด</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Urgency -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">ความเร่งด่วน</label>
                            <select name="urgency_level_id" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 p-2.5 transition-all cursor-pointer">
                                <option value="">ทั้งหมด</option>
                                @foreach($urgencyLevels as $urgency)
                                    <option value="{{ $urgency->id }}" {{ request('urgency_level_id') == $urgency->id ? 'selected' : '' }}>{{ $urgency->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">หน่วยงานเจ้าของเรื่อง</label>
                            <select name="department_id" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 p-2.5 transition-all cursor-pointer">
                                <option value="">ทั้งหมด</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-filter"></i> ค้นหา
                            </button>
                            <a href="{{ route('documents.search') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors text-center" title="รีเซ็ตตัวกรอง">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Area -->
        <div class="xl:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <!-- Result Header -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-slate-400"></i>
                        <span class="text-sm font-medium text-slate-600">ผลการค้นหาพบ <span class="text-primary-600 font-bold">{{ number_format($documents->total()) }}</span> รายการ</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 border-b border-slate-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold w-32">เลขที่หนังสือ</th>
                                <th scope="col" class="px-6 py-4 font-semibold">เรื่อง</th>
                                <th scope="col" class="px-6 py-4 font-semibold">ประเภท</th>
                                <th scope="col" class="px-6 py-4 font-semibold">หน่วยงาน</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-right">สถานะ</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($documents as $doc)
                            <tr class="group hover:bg-slate-50/80 transition-colors cursor-pointer" onclick="window.location='{{ route('documents.show', $doc) }}'">
                                
                                <!-- Col 1: ID & Date -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-mono font-bold text-primary-600 text-sm group-hover:text-primary-700 transition-colors">
                                            {{ $doc->document_no }}
                                        </span>
                                        <span class="text-xs text-slate-400 mt-1">
                                            {{ \Carbon\Carbon::parse($doc->document_date)->addYears(543)->format('d/m/y') }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Col 2: Title -->
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-800 line-clamp-2 group-hover:text-primary-700 transition-colors">{{ $doc->title }}</div>
                                    @if($doc->urgency->name !== 'ปกติ')
                                        <span class="inline-flex mt-1 items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold border" 
                                            style="color: {{ $doc->urgency->color }}; border-color: {{ $doc->urgency->color }}40; background-color: {{ $doc->urgency->color }}08;">
                                            <i class="fa-solid fa-bolt"></i> {{ $doc->urgency->name }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Col 3: Type -->
                                <td class="px-6 py-4">
                                    <span class="text-slate-600 bg-slate-100 border border-slate-200 px-2 py-1 rounded text-xs">
                                        {{ $doc->type->name }}
                                    </span>
                                </td>

                                <!-- Col 4: Department -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <i class="fa-regular fa-building text-slate-400"></i>
                                        <span class="text-xs">{{ $doc->department->name }}</span>
                                    </div>
                                </td>

                                <!-- Col 5: Status -->
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border
                                        {{ match($doc->status) {
                                            'draft' => 'bg-slate-50 text-slate-600 border-slate-200',
                                            'active' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'closed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            default => 'bg-slate-50 text-slate-600'
                                        } }}">
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
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-slate-300 group-hover:text-primary-500 group-hover:bg-primary-50 transition-all">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="flex flex-col items-center justify-center py-16 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-magnifying-glass text-4xl text-slate-300 opacity-50"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-medium text-lg">ไม่พบข้อมูล</h3>
                                        <p class="text-slate-500 text-sm mt-1 max-w-xs mx-auto">
                                            ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือล้างตัวกรองเพื่อดูข้อมูลทั้งหมด
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($documents->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection