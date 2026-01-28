<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::orderBy('id', 'asc')->paginate(10);
        return view('admin.document_types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.document_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name',
        ], ['name.unique' => 'ชื่อประเภทเอกสารนี้มีอยู่แล้ว']);

        DocumentType::create($request->all());

        return redirect()->route('admin.document_types.index')->with('success', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
    }

    public function edit(DocumentType $documentType)
    {
        return view('admin.document_types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name,'.$documentType->id,
        ]);

        $documentType->update($request->all());

        return redirect()->route('admin.document_types.index')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy(DocumentType $documentType)
    {
        // ป้องกันการลบถ้ามีเอกสารใช้งานอยู่
        // (ต้องเพิ่ม method documents() ใน Model DocumentType ก่อน หรือเช็ค manual)
        // เพื่อความง่ายในตัวอย่างนี้ อนุญาตให้ลบได้เลย หรือจะใส่ try-catch ก็ได้
        
        try {
            $documentType->delete();
            return back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากข้อมูลถูกใช้งานอยู่');
        }
    }
}