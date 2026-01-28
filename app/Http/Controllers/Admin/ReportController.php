<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Department;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. สถิติรวมทั้งหมด
        $totalDocuments = Document::count();
        
        $thisMonthDocuments = Document::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $completedDocuments = Document::where('status', 'closed')->count();

        // 2. แยกตามประเภทเอกสาร
        $docsByType = Document::select('document_type_id', DB::raw('count(*) as total'))
            ->groupBy('document_type_id')
            ->with('type')
            ->get();

        // 3. แยกตามหน่วยงาน (Top 5)
        $docsByDept = Document::select('department_id', DB::raw('count(*) as total'))
            ->groupBy('department_id')
            ->with('department')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 4. แยกตามเดือน (ย้อนหลัง 6 เดือน) - ปรับปรุง groupBy ให้ปลอดภัยขึ้น
        $monthlyStats = Document::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")')) // ใช้ Full Expression แทน Alias
            ->orderBy('month')
            ->get();

        // บรรทัดที่ 53: เรียก View
        return view('admin.reports.index', compact(
            'totalDocuments', 
            'thisMonthDocuments', 
            'completedDocuments',
            'docsByType',
            'docsByDept',
            'monthlyStats'
        ));
    }
}