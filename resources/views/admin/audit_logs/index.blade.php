@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-shoe-prints mr-2 text-primary-600"></i>Audit Logs (ประวัติการใช้งาน)</h1>
    <p class="text-slate-500 text-sm pl-9">ตรวจสอบพฤติกรรมการใช้งานระบบของผู้ใช้งาน</p>
</div>

<!-- Filter -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
    <form action="{{ route('admin.audit_logs.index') }}" method="GET" class="w-full lg:w-auto flex-1 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 outline-none" placeholder="ค้นหาชื่อผู้ใช้, เลขที่เอกสาร, รายละเอียด...">
        </div>
        <div class="w-full md:w-48">
            <select name="action" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 outline-none">
                <option value="">-- ทุกการกระทำ --</option>
                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>สร้างเอกสาร</option>
                <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>เปิดดู</option>
                <option value="process" {{ request('action') == 'process' ? 'selected' : '' }}>ดำเนินการ (Workflow)</option>
                <option value="download" {{ request('action') == 'download' ? 'selected' : '' }}>ดาวน์โหลดไฟล์</option>
                <option value="search" {{ request('action') == 'search' ? 'selected' : '' }}>ค้นหา</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors whitespace-nowrap">
                <i class="fa-solid fa-filter mr-1"></i> กรองข้อมูล
            </button>
            <a href="{{ route('admin.audit_logs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium rounded-lg text-sm px-4 py-2.5 text-center" title="ล้างตัวกรอง">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>

    <div class="w-full lg:w-auto border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-100 flex justify-end">
        <form action="{{ route('admin.audit_logs.clear') }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะล้างข้อมูลประวัติการใช้งานทั้งหมด? การกระทำนี้ไม่สามารถย้อนกลับได้');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-trash-can"></i> เคลียร์ข้อมูล
            </button>
        </form>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3">วัน-เวลา</th>
                    <th class="px-6 py-3">ผู้ใช้งาน</th>
                    <th class="px-6 py-3">การกระทำ (Action)</th>
                    <th class="px-6 py-3">รายละเอียด</th>
                    <th class="px-6 py-3">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                        {{ $log->created_at->toThaiDateTime() }}
                    </td>
                    <td class="px-6 py-4">
                        @if($log->user)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-500 font-bold overflow-hidden">
                                    @if($log->user->avatar)
                                        <img src="{{ route('storage.file', ['path' => $log->user->avatar]) }}" alt="{{ $log->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($log->user->name, 0, 1) }}
                                    @endif
                                </div>  
                                <span class="font-medium text-slate-700">{{ $log->user->name }}</span>
                            </div>
                        @else
                            <span class="text-slate-400 italic">Unknown</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ match($log->action) {
                                'login' => 'bg-green-100 text-green-800',
                                'create' => 'bg-blue-100 text-blue-800',
                                'process' => 'bg-purple-100 text-purple-800',
                                'download' => 'bg-yellow-100 text-yellow-800',
                                'delete' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }}">
                            {{ strtoupper($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-700">{{ $log->description }}</div>
                        @if($log->resource_id)
                            <div class="text-xs text-slate-400 mt-0.5">Ref ID: {{ $log->resource_id }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-500">
                        {{ $log->ip_address }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                        ไม่พบข้อมูลประวัติการใช้งาน
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection