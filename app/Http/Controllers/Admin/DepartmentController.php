<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * แสดงรายการหน่วยงานทั้งหมด
     */
    public function index()
    {
        // ดึงข้อมูลหน่วยงานทั้งหมด เรียงจากล่าสุดไปเก่า แบ่งหน้าละ 10 รายการ
        $departments = Department::orderBy('id', 'desc')->paginate(10);
        
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     * แสดงแบบฟอร์มเพิ่มหน่วยงานใหม่
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     * บันทึกข้อมูลหน่วยงานใหม่ลงฐานข้อมูล
     */
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'nullable|string|max:50|unique:departments,code',
        ], [
            // ข้อความแจ้งเตือนภาษาไทย
            'name.required' => 'กรุณาระบุชื่อหน่วยงาน',
            'name.unique' => 'ชื่อหน่วยงานนี้มีอยู่ในระบบแล้ว',
            'code.unique' => 'รหัสหน่วยงานนี้ซ้ำกับที่มีอยู่',
        ]);

        // 2. บันทึกข้อมูล
        Department::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        // 3. ส่งกลับไปหน้ารายการพร้อมข้อความแจ้งเตือน
        return redirect()->route('admin.departments.index')
            ->with('success', 'เพิ่มหน่วยงานเรียบร้อยแล้ว');
    }

    /**
     * Show the form for editing the specified resource.
     * แสดงแบบฟอร์มแก้ไขข้อมูลหน่วยงาน
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     * บันทึกการแก้ไขข้อมูลหน่วยงาน
     */
    public function update(Request $request, Department $department)
    {
        // 1. ตรวจสอบความถูกต้อง (Validate)
        $request->validate([
            // unique:ตาราง,คอลัมน์,IDที่ยกเว้น (เพื่อให้ใช้ชื่อเดิมได้ตอนแก้ไข)
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'nullable|string|max:50|unique:departments,code,' . $department->id,
        ], [
            'name.required' => 'กรุณาระบุชื่อหน่วยงาน',
            'name.unique' => 'ชื่อหน่วยงานนี้มีอยู่ในระบบแล้ว',
        ]);

        // 2. อัปเดตข้อมูล
        $department->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        // 3. ส่งกลับ
        return redirect()->route('admin.departments.index')
            ->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     * ลบข้อมูลหน่วยงาน
     */
    public function destroy(Department $department)
    {
        // 1. เช็คความสัมพันธ์ (Data Integrity Check)
        $errors = [];

        if ($department->users()->exists()) {
            $errors[] = 'มีบุคลากรสังกัดหน่วยงานนี้';
        }

        if ($department->documents()->exists()) {
            $errors[] = 'มีเอกสารที่สร้างโดยหน่วยงานนี้';
        }

        if ($department->receivedRoutes()->exists()) {
            $errors[] = 'มีประวัติการรับหนังสือของหน่วยงานนี้';
        }

        if ($department->sequences()->where('current_number', '>', 0)->exists()) {
            $errors[] = 'มีการใช้งานเลขรับ-ส่งหนังสือแล้ว';
        }

        if (!empty($errors)) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจาก: ' . implode(', ', $errors));
        }

        // 2. ลบข้อมูล (Soft Delete หรือ Hard Delete ตาม Model)
        try {
            // ลบ Sequence ที่ยังไม่ได้ใช้ (ถ้ามี) ก่อนลบ Department
            $department->sequences()->delete();
            
            $department->delete();
            return back()->with('success', 'ลบหน่วยงานเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }
}