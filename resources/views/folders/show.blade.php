@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('folders.index') }}" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-50 shadow-sm transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-800">{{ $folder->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">{{ $folder->year }}</span>
            </div>
            <p class="text-slate-500 text-sm mt-1">{{ $folder->description ?? 'ไม่มีคำอธิบาย' }}</p>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="fa-solid fa-file-contract text-slate-400"></i> เอกสารในแฟ้ม
            </h3>
            <span class="text-sm font-medium text-slate-500">{{ $documents->count() }} รายการ</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">เลขที่ / เรื่อง</th>
                        <th class="px-6 py-4 font-semibold">ผู้สร้าง</th>
                        <th class="px-6 py-4 font-semibold text-right">วันที่ลงแฟ้ม</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($documents as $doc)
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
                             <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                    @if($doc->user->avatar)
                                        <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-slate-500">{{ substr($doc->user->name, 0, 1) }}</div>
                                    @endif
                                </div>
                                <span class="text-slate-600">{{ $doc->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-slate-600 font-medium">{{ \Carbon\Carbon::parse($doc->updated_at)->toThaiDate() }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                            <i class="fa-regular fa-folder-open text-4xl mb-3 opacity-50 block"></i>
                            ยังไม่มีเอกสารในแฟ้มนี้
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
