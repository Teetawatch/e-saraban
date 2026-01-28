@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6"><h1 class="text-2xl font-bold text-slate-800">เพิ่มประเภทเอกสาร</h1></div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('admin.document_types.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-slate-700">ชื่อประเภท <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 focus:ring-primary-500 outline-none" placeholder="เช่น หนังสือเวียน, คำสั่งแต่งตั้ง" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5">บันทึก</button>
                <a href="{{ route('admin.document_types.index') }}" class="text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-medium rounded-lg text-sm px-5 py-2.5">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection