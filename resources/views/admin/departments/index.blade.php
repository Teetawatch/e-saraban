@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                <i class="fa-solid fa-building-user mr-2 text-primary-600"></i>จัดการหน่วยงาน
            </h1>
            <p class="text-slate-500 text-sm mt-1">รายชื่อหน่วยงานและโครงสร้างองค์กร</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus mr-2"></i> เพิ่มหน่วยงานใหม่
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <i class="fa-solid fa-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <i class="fa-solid fa-circle-exclamation text-xl"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div class="text-sm font-medium text-slate-600">
                ทั้งหมด <span class="text-primary-600 font-bold ml-1">{{ $departments->total() }}</span> หน่วยงาน
            </div>
            <!-- (Optional) Search could go here -->
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-24">รหัส</th>
                        <th class="px-6 py-4 font-semibold">ชื่อหน่วยงาน</th>
                        <th class="px-6 py-4 font-semibold text-center w-40">บุคลากร</th>
                        <th class="px-6 py-4 font-semibold text-right w-32">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($departments as $dept)
                    <tr class="group hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded text-xs border border-slate-200">
                                {{ $dept->code ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800 text-base group-hover:text-primary-700 transition-colors">
                                {{ $dept->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <i class="fa-solid fa-users mr-1.5 opacity-60"></i> {{ $dept->users->count() }} คน
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.departments.edit', $dept) }}" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="แก้ไข">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการลบข้อมูลนี้? หากลบแล้วจะไม่สามารถกู้คืนได้');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ลบ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-regular fa-building text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 text-sm">ยังไม่มีข้อมูลหน่วยงาน</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($departments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $departments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection