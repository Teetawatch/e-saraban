<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log($action, $module, $resourceId = null, $description = null)
    {
        AuditLog::create([
            'user_id' => Auth::id(), // เก็บ ID คนที่ล็อกอินอยู่ (ถ้ามี)
            'action' => $action,
            'module' => $module,
            'resource_id' => $resourceId,
            'description' => $description,
            'ip_address' => request()->ip(), // ใช้ request() helper แทน Request::ip()
            'user_agent' => request()->userAgent(), // ใช้ request() helper แทน Request::userAgent()
        ]);
    }
}