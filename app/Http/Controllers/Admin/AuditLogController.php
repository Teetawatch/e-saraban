<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                  ->orWhere('resource_id', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%$search%");
                  });
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.audit_logs.index', compact('logs'));
    }
    public function clear()
    {
        AuditLog::truncate();
        return redirect()->back()->with('success', 'ล้างข้อมูลประวัติการใช้งานทั้งหมดเรียบร้อยแล้ว');
    }
}