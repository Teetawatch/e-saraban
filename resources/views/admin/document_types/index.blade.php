@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-sliders text-lg"></i>
                </div>
                <div>
                    <span>ตั้งค่าประเภทเอกสาร</span>
                    <span class="block text-sm font-normal text-slate-500 mt-0.5">จัดการชื่อประเภทหนังสือในระบบ</span>
                </div>
            </h1>
        </div>
        <a href="{{ route('admin.document_types.create') }}" class="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-primary-200 transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มประเภทใหม่
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-5xl">
        <div class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-20 text-center">#</th>
                            <th class="px-6 py-4 font-semibold">ชื่อประเภทเอกสาร</th>
                            <th class="px-6 py-4 font-semibold text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($types as $index => $type)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-400 font-mono">
                                {{ $types->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-700 group-hover:text-primary-700 transition-colors">{{ $type->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.document_types.edit', $type) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-600 hover:bg-primary-50 transition-all" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.document_types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการลบประเภทเอกสารนี้?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="ลบ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($types->hasPages())
                <div class="mt-6 pt-6 border-t border-slate-100">
                    {{ $types->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection