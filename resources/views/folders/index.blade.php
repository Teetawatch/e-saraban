@extends('layouts.app')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-8" x-data="{ openModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-folder-tree text-lg"></i>
                </div>
                <div>
                    <span>ตู้เก็บเอกสารออนไลน์</span>
                    <span class="block text-sm font-normal text-slate-500 mt-0.5">Electronic Filing Cabinet</span>
                </div>
            </h1>
        </div>
        <div>
            <button @click="openModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors flex items-center gap-2 shadow-md shadow-indigo-200">
                <i class="fa-solid fa-plus"></i> สร้างแฟ้มใหม่
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check"></i>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Folders Grid -->
    @if($folders->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($folders as $folder)
            <a href="{{ route('folders.show', $folder) }}" class="group relative bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-indigo-300 transition-all duration-300 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-2xl flex items-center justify-center text-4xl text-indigo-400 mb-4 group-hover:scale-110 transition-transform duration-300 group-hover:bg-indigo-100 group-hover:text-indigo-600">
                    <i class="fa-solid fa-folder-closed"></i>
                </div>
                
                <h3 class="font-bold text-slate-800 text-lg mb-1 group-hover:text-indigo-700 transition-colors line-clamp-1 w-full">{{ $folder->name }}</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-1">{{ $folder->description ?? 'ไม่มีคำอธิบาย' }}</p>
                
                <div class="mt-auto w-full pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                    <span class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded-md font-bold">{{ $folder->year }}</span>
                    <span class="text-slate-400 font-medium">{{ $folder->documents_count }} เอกสาร</span>
                </div>
            </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 border-dashed p-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <i class="fa-solid fa-folder-open text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">ยังไม่มีแฟ้มเอกสาร</h3>
            <p class="text-slate-500 mb-6">สร้างแฟ้มใหม่เพื่อเริ่มจัดเก็บเอกสารของคุณ</p>
            <button @click="openModal = true" class="text-indigo-600 font-bold hover:underline">สร้างแฟ้มใหม่เลย</button>
        </div>
    @endif
    <!-- Create Folder Modal -->
    <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('folders.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-folder-plus text-indigo-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">สร้างแฟ้มใหม่</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">ชื่อแฟ้ม <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="เช่น แฟ้มคำสั่งปี 2568">
                                    </div>
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">รายละเอียด (ถ้ามี)</label>
                                        <textarea name="description" id="description" rows="2" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="คำอธิบายเพิ่มเติม..."></textarea>
                                    </div>
                                    <div>
                                        <label for="year" class="block text-sm font-medium text-slate-700 mb-1">ปีที่จัดเก็บ</label>
                                        <input type="text" name="year" id="year" value="{{ date('Y') + 543 }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            บันทึก
                        </button>
                        <button @click="openModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
