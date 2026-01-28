@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pb-10">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-arrow-down-1-9 text-lg"></i>
                </div>
                <div>
                    <span>ตั้งค่าเลขหนังสือ</span>
                    <span class="block text-sm font-normal text-slate-500 mt-0.5">กำหนดเลขเริ่มต้นสำหรับปี {{ $year }}</span>
                </div>
            </h1>
        </div>
        <div>
            <form action="{{ route('admin.sequences.reset') }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะรีเซ็ทข้อมูลเลขหนังสือทั้งหมด? ข้อมูลเลขหนังสือที่รันไปแล้วจะถูกล้างค่าเป็น 0 ทั้งหมด และการกระทำนี้ไม่สามารถย้อนกลับได้');">
                @csrf
                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-rotate-left"></i> รีเซ็ทข้อมูล
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3 shadow-sm">
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
                            <th class="px-6 py-4 font-semibold">หน่วยงาน</th>
                            <th class="px-6 py-4 font-semibold">เลขส่งล่าสุด (Send)</th>
                            <th class="px-6 py-4 font-semibold">เลขรับล่าสุด (Receive)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($departments as $dept)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $dept->name }}
                                </td>
                                
                                <!-- Send Number -->
                                <td class="px-6 py-4">
                                    @php
                                        $key = $dept->id . '-send';
                                        $seq = $sequences[$key] ?? null;
                                        $val = $seq ? $seq->current_number : 0;
                                        $locked = $seq ? $seq->is_locked : false;
                                    @endphp
                                    
                                    <form action="{{ route('admin.sequences.update') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="department_id" value="{{ $dept->id }}">
                                        <input type="hidden" name="type" value="send">
                                        
                                        <input type="number" name="current_number" value="{{ $val }}" 
                                            class="w-24 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all shadow-sm {{ $locked ? 'bg-slate-100 text-slate-500' : '' }}"
                                            {{ $locked ? 'disabled' : '' }}>
                                        
                                        @if(!$locked)
                                            <button type="submit" class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="บันทึก">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400 italic flex items-center gap-1">
                                                <i class="fa-solid fa-lock"></i> ใช้งานแล้ว
                                            </span>
                                        @endif
                                    </form>
                                </td>

                                <!-- Receive Number -->
                                <td class="px-6 py-4">
                                    @php
                                        $key = $dept->id . '-receive';
                                        $seq = $sequences[$key] ?? null;
                                        $val = $seq ? $seq->current_number : 0;
                                        $locked = $seq ? $seq->is_locked : false;
                                    @endphp
                                    
                                    <form action="{{ route('admin.sequences.update') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="department_id" value="{{ $dept->id }}">
                                        <input type="hidden" name="type" value="receive">
                                        
                                        <input type="number" name="current_number" value="{{ $val }}" 
                                            class="w-24 rounded-lg border-slate-200 text-sm focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all shadow-sm {{ $locked ? 'bg-slate-100 text-slate-500' : '' }}"
                                            {{ $locked ? 'disabled' : '' }}>
                                        
                                        @if(!$locked)
                                            <button type="submit" class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="บันทึก">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400 italic flex items-center gap-1">
                                                <i class="fa-solid fa-lock"></i> ใช้งานแล้ว
                                            </span>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
