@extends('layouts.app')

@section('content')
<style>
    .doc-row {
        transition: all 0.2s ease-in-out;
    }
    .doc-row:hover {
        background-color: #f8fafc; /* slate-50 */
    }
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
<div class="min-h-screen bg-slate-50/30">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-[250px] bg-gradient-to-b from-indigo-50/50 via-white to-transparent -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header Navigation & Info --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
            <div class="flex items-start gap-4">
                <a href="{{ route('folders.index') }}" 
                   class="group flex items-center justify-center w-12 h-12 rounded-xl bg-white border border-slate-200 text-slate-400 shadow-sm hover:text-indigo-600 hover:border-indigo-100 hover:shadow-md transition-all duration-300">
                    <i class="fa-solid fa-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">{{ $folder->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 text-xs font-bold shadow-sm uppercase tracking-wider">
                            <i class="fa-regular fa-calendar mr-1"></i> {{ $folder->year }}
                        </span>
                    </div>
                    <p class="text-slate-500 flex items-center gap-2 text-sm md:text-base">
                        <i class="fa-regular fa-folder-open text-slate-400"></i>
                         {{ $folder->description ?? 'ไม่มีคำอธิบายเพิ่มเติม' }}
                    </p>
                </div>
            </div>

            {{-- Stats Badge --}}
            <div class="flex items-center gap-3 animate-fade-in-up">
                <div class="px-5 py-3 bg-white rounded-xl border border-slate-200 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 text-lg">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">เอกสารในแฟ้ม</p>
                        <p class="text-2xl font-bold text-slate-800 leading-none">{{ $documents->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents Container --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center backdrop-blur-sm">
                <h3 class="font-bold text-slate-700 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> รายการเอกสาร
                </h3>
                <div class="text-xs text-slate-400 font-medium">
                    แสดงรายการล่าสุด
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 text-xs text-slate-500 uppercase font-semibold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-[60%]">เลขที่ / เรื่อง</th>
                            <th class="px-6 py-4 w-[25%]">ผู้สร้าง</th>
                            <th class="px-6 py-4 w-[15%] text-right">วันที่ลงแฟ้ม</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($documents as $doc)
                        <tr class="doc-row group cursor-pointer relative" 
                            onclick="window.location='{{ route('documents.show', $doc) }}'">

                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-indigo-500 shrink-0 group-hover:bg-white group-hover:shadow-sm group-hover:scale-110 transition-all duration-300">
                                        <i class="fa-regular fa-file-lines text-lg"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-slate-800 text-base mb-1 group-hover:text-indigo-700 transition-colors line-clamp-1">
                                            {{ $doc->title }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="font-mono font-medium text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded text-[10px] border border-slate-200 group-hover:bg-white transition-colors">
                                                {{ $doc->document_no }}
                                            </span>
                                            @if($doc->urgency && $doc->urgency->name !== 'ปกติ')
                                            <span class="inline-flex items-center gap-1 text-[10px] px-1.5 py-0.5 rounded font-bold border" 
                                                style="color: {{ $doc->urgency->color }}; background-color: {{ $doc->urgency->color }}10; border-color: {{ $doc->urgency->color }}30;">
                                                <i class="fa-solid fa-bolt text-[8px]"></i> {{ $doc->urgency->name }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                 <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden flex-shrink-0 ring-2 ring-white shadow-sm group-hover:ring-indigo-100 transition-all">
                                        @if($doc->user->avatar)
                                            <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">
                                                {{ substr($doc->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">{{ $doc->user->name }}</p>
                                        <p class="text-[10px] text-slate-400 group-hover:text-indigo-400 transition-colors">
                                            @if($doc->user->department)
                                                {{ $doc->user->department->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-slate-600 font-medium text-sm group-hover:text-indigo-600 transition-colors">
                                    {{ \Carbon\Carbon::parse($doc->updated_at)->toThaiDate() }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($doc->updated_at)->format('H:i') }} น.
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-20 text-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i class="fa-regular fa-folder-open text-4xl text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-600">ยังไม่มีเอกสารในแฟ้มนี้</h3>
                                <p class="text-slate-400 mt-1">เอกสารที่คุณจัดเก็บลงแฟ้มจะปรากฏที่นี่</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($documents, 'links'))
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
