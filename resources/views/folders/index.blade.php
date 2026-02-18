@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/30" x-data="{ openModal: false }">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-[300px] bg-gradient-to-b from-indigo-50/50 via-white to-transparent -z-10"></div>
    
    {{-- Decorative Orbs --}}
    <div class="fixed top-20 right-0 w-[500px] h-[500px] bg-purple-200/20 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse" style="animation-duration: 4s;"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] bg-indigo-200/20 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold mb-4">
                    <i class="fa-solid fa-archive"></i> ระบบจัดการแฟ้ม
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight mb-2">
                    ตู้เก็บเอกสารออนไลน์
                </h1>
                <p class="text-slate-500 text-lg">Electronic Filing Cabinet</p>
            </div>
            
            <button @click="openModal = true" 
                class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-300 bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5">
                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center mr-2 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-plus text-xs"></i>
                </span>
                สร้างแฟ้มใหม่
            </button>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-8 p-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl shadow-lg shadow-emerald-500/10 animate-fade-in-down">
                <div class="bg-white rounded-xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 text-lg">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">ดำเนินการสำเร็จ!</h4>
                        <p class="text-sm text-slate-500">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Folders Grid --}}
        @if($folders->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($folders as $folder)
                <a href="{{ route('folders.show', $folder) }}" class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1 overflow-hidden h-full flex flex-col">
                    {{-- Hover Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/30 to-purple-50/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    
                    {{-- Folder Icon --}}
                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-[-5deg] group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm group-hover:shadow-indigo-300">
                            <i class="fa-solid fa-folder-closed group-hover:hidden"></i>
                            <i class="fa-solid fa-folder-open hidden group-hover:block animate-bounce-short"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider group-hover:bg-white group-hover:text-indigo-600 group-hover:border-indigo-100 transition-colors">
                            {{ $folder->year }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="relative z-10 flex-1 flex flex-col">
                        <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-700 transition-colors line-clamp-1">{{ $folder->name }}</h3>
                        <p class="text-sm text-slate-500 mb-6 line-clamp-2 h-[40px]">{{ $folder->description ?? 'ไม่มีรายละเอียดเพิ่มเติม' }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-slate-100 group-hover:border-indigo-100/50 transition-colors flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-regular fa-file-alt text-slate-400 group-hover:text-indigo-400 transition-colors"></i>
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-indigo-600 transition-colors">
                                    {{ $folder->documents_count }} เอกสาร
                                </span>
                            </div>
                            <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-dashed border-slate-300 p-16 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-slate-50/50 pattern-grid-lg opacity-20"></div>
                
                <div class="relative z-10">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-white">
                        <i class="fa-solid fa-folder-plus text-4xl text-indigo-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">ยังไม่มีแฟ้มเอกสาร</h3>
                    <p class="text-slate-500 mb-8 max-w-sm mx-auto">เริ่มจัดระเบียบเอกสารของคุณอย่างมืออาชีพ ด้วยการสร้างแฟ้มหมวดหมู่ใหม่</p>
                    <button @click="openModal = true" class="text-indigo-600 font-bold hover:text-indigo-700 hover:underline decoration-2 underline-offset-4 transition-all">
                        <i class="fa-solid fa-arrow-right mr-1"></i> สร้างแฟ้มใหม่เลย
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Create Folder Modal --}}
    <div x-show="openModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        {{-- Backdrop --}}
        <div x-show="openModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="openModal" 
                     @click.away="openModal = false"
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                            <i class="fa-solid fa-folder-plus text-white/80"></i> สร้างแฟ้มใหม่
                        </h3>
                        <button @click="openModal = false" class="text-white/70 hover:text-white transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('folders.store') }}" method="POST">
                        @csrf
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">ชื่อแฟ้ม <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required 
                                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-shadow" 
                                    placeholder="เช่น แฟ้มคำสั่งปี 2568">
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-semibold text-slate-700 mb-1.5">ปีที่จัดเก็บ</label>
                                <input type="text" name="year" id="year" value="{{ date('Y') + 543 }}" 
                                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 bg-slate-50 transition-shadow">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">รายละเอียด <span class="text-slate-400 font-normal">(ถ้ามี)</span></label>
                                <textarea name="description" id="description" rows="3" 
                                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-shadow" 
                                    placeholder="อธิบายเกี่ยวกับเอกสารในแฟ้มนี้..."></textarea>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex w-full justify-center items-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto transition-all">
                                <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
                            </button>
                            <button type="button" @click="openModal = false" class="mt-3 inline-flex w-full justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all">
                                ยกเลิก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10%); }
    }
    .animate-bounce-short {
        animation: bounce-short 0.5s ease-in-out;
    }
</style>
@endsection
