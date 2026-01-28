<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\Document;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// หน้าแรก: ถ้า Login แล้วให้ไป Dashboard ถ้ายังให้ไปหน้า Login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Fallback for Storage (Shared Hosting Fix)
// ใช้วิธีนี้แทนการทำ Symlink เนื่องจากบน Shared Host บางทีทำไม่ได้
Route::get('storage/{path}', \App\Http\Controllers\StorageController::class)->where('path', '.*')->name('storage.file');

// กลุ่ม Route ที่ต้อง Login เท่านั้นถึงจะเข้าได้
Route::middleware(['auth'])->group(function () {

    // --- Dashboard (พร้อม Logic ดึงข้อมูลจริงจาก DB) ---
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Query สำหรับนับจำนวน (Stats)
        
        // 1. Inbox Count: เอกสารที่ส่งถึงเรา หรือ หน่วยงานเรา (รวมถึง Inbound ที่ลงทะเบียนเองด้วย)
        // Logic นี้ต้องตรงกับ DocumentController::index Tab 'inbox'
        $inboxQuery = Document::whereHas('routes', function($r) use ($user) {
            $r->where('to_user_id', $user->id)
              ->orWhere('to_department_id', $user->department_id);
        });
        $inboxCount = $inboxQuery->count();

        // 2. Outbox Count: เอกสารที่หน่วยงานเราสร้าง (นับทั้งหมด)
        // Logic นี้ต้องตรงกับ DocumentController::index Tab 'outbox'
        $outboxCount = Document::where('department_id', $user->department_id)->count();

        // 3. Active Count: เรื่องที่กำลังดำเนินการ (นับจาก Inbox)
        $activeCount = (clone $inboxQuery)->where('status', 'active')->count();

        // 4. Urgent Count: เรื่องด่วนที่สุด (นับจาก Inbox)
        $urgentCount = (clone $inboxQuery)->whereHas('urgency', function($q) {
            $q->where('name', 'ด่วนที่สุด');
        })->count();

        // 5. ดึงรายการล่าสุด 5 รายการ เพื่อแสดงในตารางด้านล่าง
        // [สำคัญ] เพิ่ม 'routes' ใน with() เพื่อใช้เช็ค Logic ในหน้า Dashboard ว่าใครรับเรื่องล่าสุด
        $recentDocuments = (clone $inboxQuery)
            ->with(['type', 'urgency', 'user', 'routes'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // 6. ดึงรายการ "หนังสือเข้าใหม่ / รอดำเนินการ" (Incoming Documents)
        // แยก Query ออกมาต่างหาก เพื่อไม่ให้โดน limit(5) ของ recentDocuments บัง
        $incomingDocuments = (clone $inboxQuery)
            ->where('status', '!=', 'closed') // ต้องยังไม่ปิดงาน
            ->where('user_id', '!=', $user->id) // ต้องไม่ใช่เอกสารที่ฉันสร้างเอง
            ->with(['type', 'urgency', 'user', 'routes.fromUser'])
            ->orderBy('updated_at', 'desc')
            ->limit(20) 
            ->get()
            ->filter(function($doc) use ($user) {
                // Logic: Scan routes from NEWEST to OLDEST.
                // 1. If we find a "Receive" by Me or My Dept -> HANDLED (Don't show).
                // 2. If we find a "Send" to Me or My Dept (and haven't found a Receive yet) -> PENDING (Show).
                
                $isPending = false;
                $isHandled = false;

                foreach ($doc->routes->sortByDesc('id') as $route) {
                    
                    // Check if *Handled* (Received) by Me or My Dept
                    if ($route->action === 'receive') {
                        if ($route->from_user_id == $user->id) {
                            $isHandled = true;
                            break; 
                        }
                        if ($route->fromUser && $route->fromUser->department_id == $user->department_id) {
                            $isHandled = true;
                            break;
                        }
                    }

                    // Check if *Sent* to Me or My Dept
                    if ($route->to_user_id == $user->id || $route->to_department_id == $user->department_id) {
                        if (!$isHandled) {
                            $isPending = true;
                        }
                        break; // Stop scanning once we find the triggering 'send'
                    }
                }

                return $isPending;
            });

        // ส่งตัวแปรทั้งหมดไปที่ View
        return view('dashboard', compact('inboxCount', 'outboxCount', 'activeCount', 'urgentCount', 'recentDocuments', 'incomingDocuments'));
    })->name('dashboard');


    // --- Profile Routes (แก้ไขข้อมูลส่วนตัว) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');


    // --- Notification Routes (ระบบแจ้งเตือน) ---
    Route::get('notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('notifications/check', [NotificationController::class, 'check'])->name('notifications.check');


    // --- ส่วนจัดการเอกสาร (Documents) ---
    
    // Route ค้นหาขั้นสูง (ต้องวางไว้ *ก่อน* resource เพื่อไม่ให้ชนกับ documents/{id})
    Route::get('documents/search', [DocumentController::class, 'search'])->name('documents.search');

    // Route สำหรับ Process (ส่งต่อ/ปิดเรื่อง)
    Route::post('documents/{document}/process', [DocumentController::class, 'process'])->name('documents.process');

    // Route สำหรับดาวน์โหลดไฟล์แนบ (Secure Download & Audit Log)
    Route::get('documents/attachments/{attachment}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Resource Route ปกติ (Index, Create, Store, Show)
    Route::resource('documents', DocumentController::class);

    // --- ระบบตู้เก็บเอกสารออนไลน์ (E-Filing) ---
    Route::post('documents/{document}/file', [App\Http\Controllers\FolderController::class, 'fileDocument'])->name('documents.file');
    Route::resource('folders', App\Http\Controllers\FolderController::class);


    // --- ส่วนของผู้ดูแลระบบ (Admin Only) ---
    Route::middleware(['can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // รายงานสรุป
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        // Audit Logs (ประวัติการใช้งาน)
        Route::delete('audit-logs/clear', [AuditLogController::class, 'clear'])->name('audit_logs.clear');
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');

        // จัดการ Master Data (CRUD)
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
        Route::resource('document_types', DocumentTypeController::class);

        // จัดการเลขหนังสือ (Sequences)
        Route::get('sequences', [\App\Http\Controllers\Admin\DepartmentSequenceController::class, 'index'])->name('sequences.index');
        Route::post('sequences', [\App\Http\Controllers\Admin\DepartmentSequenceController::class, 'update'])->name('sequences.update');
        Route::post('sequences/reset', [\App\Http\Controllers\Admin\DepartmentSequenceController::class, 'reset'])->name('sequences.reset');
    });

});

// โหลด Routes ของระบบ Authentication (Login, Register, Logout)
require __DIR__.'/auth.php';