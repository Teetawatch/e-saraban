@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                <i class="fa-solid fa-users-gear mr-2 text-primary-600"></i>จัดการผู้ใช้งาน
            </h1>
            <p class="text-slate-500 text-sm mt-1">รายชื่อบุคลากรและกำหนดสิทธิ์การใช้งานระบบ</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-user-plus mr-2"></i> เพิ่มผู้ใช้งานใหม่
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
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm font-medium text-slate-600">
                ทั้งหมด <span class="text-primary-600 font-bold ml-1">{{ $users->total() }}</span> บัญชีผู้ใช้
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ชื่อ-นามสกุล / อีเมล</th>
                        <th class="px-6 py-4 font-semibold">หน่วยงาน</th>
                        <th class="px-6 py-4 font-semibold">สิทธิ์ (Role)</th>
                        <th class="px-6 py-4 font-semibold text-right w-32">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="group hover:bg-slate-50/80 transition-colors">
                        <!-- User Info (แสดงรูป Avatar) -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-sm font-bold text-slate-600 border border-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
                                    @if($user->avatar)
                                        <img src="{{ route('storage.file', ['path' => $user->avatar]) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-400 font-light">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Department -->
                        <td class="px-6 py-4">
                            @if($user->department)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                    <i class="fa-regular fa-building text-slate-400"></i> {{ $user->department->name }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic">- ไม่ระบุ -</span>
                            @endif
                        </td>

                        <!-- Roles -->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border
                                        {{ match($role->name) {
                                            'admin' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            'officer' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'manager' => 'bg-orange-50 text-orange-700 border-orange-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200'
                                        } }}">
                                        {{ $role->label }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="แก้ไข">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการลบผู้ใช้งานนี้?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="ลบ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-users-slash text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 text-sm">ยังไม่มีข้อมูลผู้ใช้งาน</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection