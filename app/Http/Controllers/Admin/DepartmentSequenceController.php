<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentSequence;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;

class DepartmentSequenceController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $year = date('Y') + 543;

        // Fetch sequences for current year
        $sequences = DepartmentSequence::where('year', $year)->get()
            ->keyBy(function ($item) {
                return $item->department_id . '-' . $item->type;
            });

        return view('admin.sequences.index', compact('departments', 'year', 'sequences'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'type' => 'required|in:send,receive',
            'current_number' => 'required|integer|min:0',
        ]);

        $year = date('Y') + 543;
        $service = new DocumentNumberService();

        try {
            $service->setInitialNumber(
                $request->department_id,
                $request->type,
                $year,
                $request->current_number
            );

            return back()->with('success', 'บันทึกค่าเริ่มต้นเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reset()
    {
        $year = date('Y') + 543;
        
        // Delete all sequences for the current year (Reset to 0)
        DepartmentSequence::where('year', $year)->delete();

        return back()->with('success', 'รีเซ็ทข้อมูลเลขหนังสือทั้งหมดเรียบร้อยแล้ว');
    }
}
