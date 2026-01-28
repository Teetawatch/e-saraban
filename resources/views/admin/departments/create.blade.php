@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">เพิ่มหน่วยงานใหม่</h1>
        <p class="text-slate-500 text-sm">กรอกข้อมูลหน่วยงานที่ต้องการเพิ่มเข้าสู่ระบบ</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('admin.departments.store') }}" method="POST">
            @csrf
            
            <div class="grid gap-6 mb-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-slate-700">ชื่อหน่วยงาน <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                        class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 outline-none transition-all" 
                        placeholder="เช่น กองกลาง, ฝ่ายบริหารงานทั่วไป" required>
                    @error('name') <p class="mt-2 text-sm text-red-600"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p> @enderror
                </div>

                <!-- Code Field -->
                <div>
                    <label for="code" class="block mb-2 text-sm font-medium text-slate-700">รหัสหน่วยงาน (ถ้ามี)</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" 
                        class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 outline-none transition-all" 
                        placeholder="เช่น 01, ADM">
                    <p class="mt-1 text-xs text-slate-400">ระบุรหัสย่อเพื่อความสะดวกในการอ้างอิง</p>
                    @error('code') <p class="mt-2 text-sm text-red-600"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors shadow-sm">
                    <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
                </button>
                <a href="{{ route('admin.departments.index') }}" class="text-slate-700 bg-white border border-slate-300 focus:ring-4 focus:ring-slate-100 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-slate-50 focus:outline-none transition-colors">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection