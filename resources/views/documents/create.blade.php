@extends('layouts.app')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-8 pb-10">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    @if($direction === 'inbound')
                        <i class="fa-solid fa-inbox mr-2 text-emerald-600"></i>ลงทะเบียนหนังสือเข้า
                    @else
                        <i class="fa-solid fa-paper-plane mr-2 text-brand-600"></i>ลงทะเบียนหนังสือออก
                    @endif
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    @if($direction === 'inbound')
                        ลงทะเบียนรับหนังสือเวียนหรือหนังสือจากภายนอกเข้าสู่ระบบ
                    @else
                        สร้างหนังสือส่งออกหรือลงทะเบียนรับหนังสือเข้าสู่ระบบ
                    @endif
                </p>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="direction" value="{{ $direction }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column: Main Info (2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Card: รายละเอียดหนังสือ -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h3
                            class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-100">
                            <span class="w-1 h-6 bg-brand-500 rounded-full"></span>
                            รายละเอียดหนังสือ
                        </h3>

                        <div class="space-y-6">
                            <!-- Subject -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700">เรื่อง <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 block p-3 transition-all placeholder:text-slate-400"
                                    placeholder="ระบุชื่อเรื่องหนังสือ..." required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Document No -->
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">เลขที่หนังสือ</label>
                                    <div
                                        class="w-full bg-slate-100 border border-slate-200 text-slate-500 rounded-xl p-3 flex items-center justify-between cursor-not-allowed select-none">
                                        <span class="text-sm">
                                            <i class="fa-solid fa-wand-magic-sparkles mr-2 text-brand-400"></i>
                                            @if($direction === 'inbound') สร้างเลขรับอัตโนมัติ @else สร้างเลขส่งอัตโนมัติ
                                            @endif
                                        </span>
                                        <span
                                            class="text-[10px] font-bold bg-slate-200 text-slate-600 px-2 py-1 rounded uppercase">Auto-Gen</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-2 ml-1">
                                        @if($direction === 'inbound')
                                            เลขที่หนังสือ (เลขรับ) จะถูกสร้างตามการตั้งค่า (เช่น 1, 2, 3...)
                                        @else
                                            เลขที่หนังสือ (เลขส่ง) จะถูกสร้างตามปี (เช่น {{ date('Y') + 543 }}/xxxx)
                                        @endif
                                    </p>
                                </div>

                                <!-- Date -->
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">ลงวันที่</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                            <i class="fa-regular fa-calendar"></i>
                                        </div>
                                        <input type="date" name="document_date"
                                            value="{{ old('document_date', date('Y-m-d')) }}"
                                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 block pl-10 p-3 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: ไฟล์แนบ -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h3
                            class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-100">
                            <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                            ไฟล์แนบเอกสาร
                        </h3>

                        <!-- Upload Zone -->
                        <div class="w-full">
                            <label for="dropzone-file"
                                class="group flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-brand-50 hover:border-brand-400 transition-all duration-300 relative overflow-hidden">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 z-10">
                                    <div
                                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <i
                                            class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 group-hover:text-brand-500 transition-colors"></i>
                                    </div>
                                    <p
                                        class="mb-2 text-sm text-slate-500 group-hover:text-brand-700 font-medium transition-colors">
                                        <span class="font-bold">คลิกเพื่อเลือกไฟล์</span> หรือลากไฟล์มาวางที่นี่</p>
                                    <p class="text-xs text-slate-400 group-hover:text-brand-500/70">รองรับ PDF, Word, Excel,
                                        รูปภาพ (สูงสุด 20MB)</p>
                                </div>
                                <!-- Background decoration -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-tr from-transparent via-transparent to-brand-100/30 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>

                                <input id="dropzone-file" name="attachments[]" type="file" class="hidden" multiple
                                    onchange="updateFileList(this)" />
                            </label>
                        </div>

                        <!-- Selected Files List -->
                        <ul id="file-list" class="mt-4 space-y-3"></ul>
                    </div>

                </div>

                <!-- Right Column: Meta Data (1/3) -->
                <div class="lg:col-span-1 space-y-8">

                    <!-- Card: คุณลักษณะ -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3
                            class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2 pb-4 border-b border-slate-100">
                            <i class="fa-solid fa-sliders text-slate-400"></i> คุณลักษณะ
                        </h3>

                        <div class="space-y-6">
                            <!-- Type -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700">ประเภทเอกสาร <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="document_type_id"
                                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-3 appearance-none cursor-pointer"
                                        required>
                                        @foreach($documentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Urgency -->
                            <div>
                                <label class="block mb-3 text-sm font-semibold text-slate-700">ความเร่งด่วน <span
                                        class="text-red-500">*</span></label>
                                <div class="space-y-3">
                                    @foreach($urgencyLevels as $level)
                                                                <label
                                                                    class="relative flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 hover:border-brand-300 transition-all group">
                                                                    <input type="radio" name="urgency_level_id" value="{{ $level->id }}"
                                                                        class="peer w-4 h-4 text-brand-600 bg-slate-100 border-slate-300 focus:ring-brand-500"
                                                                        {{ $loop->first ? 'checked' : '' }}>
                                                                    <span
                                                                        class="ml-3 text-sm font-medium text-slate-700 group-hover:text-slate-900">{{ $level->name }}</span>

                                                                    <!-- Color Indicator -->
                                                                    <span
                                                                        class="ml-auto w-2.5 h-2.5 rounded-full ring-2 ring-white shadow-sm peer-checked:scale-125 transition-transform"
                                                                        style="background-color: {{ 
                                                                            match ($level->name) {
                                            'ปกติ' => '#94a3b8',
                                            'ด่วน' => '#f97316',
                                            'ด่วนมาก' => '#ef4444',
                                            'ด่วนที่สุด' => '#7f1d1d',
                                            default => '#94a3b8'
                                        } 
                                                                        }};">
                                                                    </span>

                                                                    <!-- Active Border Highlight -->
                                                                    <div
                                                                        class="absolute inset-0 border-2 border-brand-500 rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity">
                                                                    </div>
                                                                </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Confidential -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700">ชั้นความลับ <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="confidential_level_id"
                                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand-100 focus:border-brand-500 p-3 appearance-none cursor-pointer"
                                        required>
                                        @foreach($confidentialLevels as $level)
                                            <option value="{{ $level->id }}"
                                                class="{{ $level->name !== 'ปกติ' ? 'text-red-600 font-bold' : '' }}">
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-4 pt-4 sticky top-6">
                        <button type="submit"
                            class="w-full text-white bg-brand-600 hover:bg-brand-700 font-semibold rounded-xl text-base px-5 py-3.5 shadow-lg shadow-brand-200 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 group">
                            <i class="fa-solid fa-paper-plane group-hover:animate-pulse"></i> ลงทะเบียนหนังสือ
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="w-full text-center text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 hover:text-slate-800 font-medium rounded-xl text-sm px-5 py-3.5 transition-colors">
                            ยกเลิก
                        </a>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Script สำหรับแสดงชื่อไฟล์ที่เลือก -->
    <script>
        function updateFileList(input) {
            const list = document.getElementById('file-list');
            list.innerHTML = ''; // Clear old list
            if (input.files) {
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-3 bg-white border border-slate-200 p-3 rounded-xl shadow-sm animate-fade-in-up';

                    // Icon Logic
                    let iconClass = 'fa-file text-slate-400';
                    if (file.name.endsWith('.pdf')) iconClass = 'fa-file-pdf text-red-500';
                    else if (file.name.match(/\.(jpg|jpeg|png)$/)) iconClass = 'fa-image text-purple-500';
                    else if (file.name.match(/\.(doc|docx)$/)) iconClass = 'fa-file-word text-blue-500';
                    else if (file.name.match(/\.(xls|xlsx)$/)) iconClass = 'fa-file-excel text-green-500';

                    li.innerHTML = `
                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-regular ${iconClass}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">${file.name}</p>
                            <p class="text-xs text-slate-400">${(file.size / 1024).toFixed(1)} KB</p>
                        </div>
                        <div class="text-green-500">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    `;
                    list.appendChild(li);
                }
            }
        }
    </script>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out forwards;
        }
    </style>
@endsection