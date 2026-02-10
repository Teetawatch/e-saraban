<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\UrgencyLevel;
use App\Models\ConfidentialLevel;
use App\Models\Department;
use App\Models\User;
use App\Models\DocumentAttachment;
use App\Notifications\DocumentActionNotification;
use App\Services\AuditLogger;
use App\Services\DocumentNumberService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentNumberService $documentNumberService
    ) {
    }
    /**
     * แสดงรายการหนังสือ (Inbox / Outbox)
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'inbox');
        $user = auth()->user();

        // Eager Load เพื่อลด Query
        $query = Document::with(['type', 'urgency', 'user', 'department', 'attachments', 'routes.fromUser']);

        if ($tab === 'outbox') {
            // Outbox: แสดงหนังสือทั้งหมดที่"คนในหน่วยงาน"เป็นคนสร้าง
            // แสดงทุกเอกสารที่หน่วยงานสร้าง ไม่ว่าจะมีคนรับแล้วหรือยัง
            $query->where('department_id', $user->department_id);
        } else {
            // Inbox: แสดงหนังสือที่รับเข้ามา
            // 1. ส่งถึงตัวเรา (Personal) -> รับจากใครก็ได้ (คนอื่นส่งมา)
            // 2. ส่งถึงหน่วยงานเรา (Department) -> รับจากใครก็ได้ (คนอื่นส่งมา)

            $query->whereHas('routes', function ($r) use ($user) {
                // Inbox: แสดงหนังสือที่ส่งถึงตัวเรา หรือ ส่งถึงหน่วยงานเรา
                // ตัดเงื่อนไข where('from_user_id', '!=', $user->id) ออก เพื่อให้เห็นหนังสือที่ส่งหาตัวเอง/หน่วยงานตัวเองได้
                $r->where('to_user_id', $user->id)
                    ->orWhere('to_department_id', $user->department_id);
            });
        }

        // Search Filter ง่ายๆ สำหรับช่องค้นหาหน้า Index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_no', 'like', "%$search%")
                    ->orWhere('title', 'like', "%$search%");
            });
        }

        $documents = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        return view('documents.index', compact('documents', 'tab'));
    }

    /**
     * หน้าค้นหาเอกสารขั้นสูง (Advanced Search)
     */
    public function search(Request $request): View
    {
        // [LOG] บันทึกพฤติกรรมการค้นหา
        AuditLogger::log('search', 'document', null, 'ค้นหาเอกสาร: ' . json_encode($request->except('_token', 'page')));

        $documentTypes = DocumentType::all();
        $urgencyLevels = UrgencyLevel::all();
        $departments = Department::all();



        // [FIX] Apply Accessibility Scope
        $query = Document::accessibleBy(auth()->user())
            ->with(['type', 'urgency', 'department', 'user']);

        // กรองตามเงื่อนไขต่างๆ
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('document_no', 'like', "%$keyword%")
                    ->orWhere('title', 'like', "%$keyword%");
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('document_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('document_date', '<=', $request->end_date);
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('urgency_level_id')) {
            $query->where('urgency_level_id', $request->urgency_level_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $documents = $query->orderBy('document_date', 'desc')->paginate(15)->withQueryString();

        return view('documents.search', compact('documents', 'documentTypes', 'urgencyLevels', 'departments'));
    }

    /**
     * แสดงฟอร์มลงทะเบียนหนังสือใหม่
     */
    public function create(): View
    {
        $documentTypes = DocumentType::all();
        $urgencyLevels = UrgencyLevel::all();
        $confidentialLevels = ConfidentialLevel::all();
        $departments = Department::all();

        $direction = request()->query('direction', 'outbound');
        return view('documents.create', compact('documentTypes', 'urgencyLevels', 'confidentialLevels', 'departments', 'direction'));
    }

    /**
     * บันทึกข้อมูลหนังสือใหม่
     */
    public function store(Request $request): RedirectResponse
    {
        $direction = $request->input('direction', 'outbound');

        $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'urgency_level_id' => 'required|exists:urgency_levels,id',
            'confidential_level_id' => 'required|exists:confidential_levels,id',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:20480',
            // Inbound requires manual document number -> CHANGED to Auto-Gen from Receive Sequence
            'document_no' => $direction === 'inbound' ? 'nullable' : 'nullable',
        ]);

        DB::beginTransaction();

        try {

            // Document Number & Route Logic
            if ($direction === 'outbound') {
                // Outbound: Auto-generate Document No (Send No)
                $documentNo = $this->documentNumberService->getNextSendNumber(auth()->user()->department_id);
                $status = 'draft';
                $action = 'created';
                $note = 'สร้าง/ลงทะเบียนหนังสือออก';
                $receiveNo = null;
            } else {
                // Inbound: Auto-generate Document No using Receive Sequence (per user request)
                $receiveNo = $this->documentNumberService->getNextReceiveNumber(auth()->user()->department_id);

                // Use "Receive No" as the Document No (possibly formatted)
                $documentNo = "รับ-" . $receiveNo;

                $status = 'active';
                $action = 'receive';
                $note = 'ลงทะเบียนรับหนังสือเข้า (Auto-Gen)';
            }


            $document = Document::create([
                'document_no' => $documentNo,
                'title' => $request->title,
                'document_date' => $request->document_date ?? now(),
                'document_type_id' => $request->document_type_id,
                'urgency_level_id' => $request->urgency_level_id,
                'confidential_level_id' => $request->confidential_level_id,
                'user_id' => auth()->id(),
                'department_id' => auth()->user()->department_id,
                'status' => $status,
            ]);

            // อัปโหลดไฟล์
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    // เก็บไฟล์ใน public disk (storage/app/public/documents)
                    $filePath = $file->storeAs('documents', $fileName, 'public');

                    DocumentAttachment::create([
                        'document_id' => $document->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            // Prepare Route Data
            $routeData = [
                'from_user_id' => auth()->id(),
                'action' => $action,
                'note' => $note,
            ];

            if ($direction === 'inbound') {
                $routeData['receive_no'] = $receiveNo; // Used the pre-generated one
                $routeData['receive_date'] = now();
                $routeData['to_department_id'] = auth()->user()->department_id;
            }

            // บันทึก Route แรก
            $document->routes()->create($routeData);

            // [LOG]
            AuditLogger::log('create', 'document', $document->document_no, 'สร้างหนังสือ: ' . $document->title);

            DB::commit();

            return redirect()->route('documents.show', $document)->with('success', "ลงทะเบียนหนังสือเลขที่ $documentNo เรียบร้อยแล้ว");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * แสดงรายละเอียดหนังสือ
     */
    public function show(Document $document): View
    {
        // [FIX] Security: Check Accessibility
        if (Document::accessibleBy(auth()->user())->where('id', $document->id)->doesntExist()) {
            abort(403);
        }

        // [LOG]
        AuditLogger::log('view', 'document', $document->document_no, 'เปิดดูรายละเอียดหนังสือ');

        $document->load(['attachments', 'user', 'department', 'type', 'urgency', 'confidential', 'routes.fromUser', 'routes.toUser', 'routes.toDepartment']);

        // Fetch Audit Logs (Views) - Show only latest view per user
        $logs = \App\Models\AuditLog::with('user')
            ->where('module', 'document')
            ->where('action', 'view')
            ->where('resource_id', $document->document_no)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('user_id') // Keep only the latest view for each user
            ->take(10) // Limit to 10 latest unique viewers
            ->map(function ($log) {
                $log->type = 'view';
                return $log;
            });

        // Merge with Routes
        $history = $document->routes->map(function ($route) {
            $route->type = 'action';
            $route->user = $route->fromUser; // Map fromUser to user for consistency
            return $route;
        })->concat($logs)->sortByDesc('created_at')->values();

        // ข้อมูลสำหรับ Modal ส่งต่อ (ส่งหาคนอื่นที่ไม่ใช่ตัวเอง)
        $users = User::where('id', '!=', auth()->id())->get();
        $departments = Department::all();

        // ข้อมูลสำหรับ Modal เข้าแฟ้ม (E-Filing)
        $folders = \App\Models\Folder::where('department_id', auth()->user()->department_id)
            ->orderBy('year', 'desc')
            ->latest()
            ->get();

        return view('documents.show', compact('document', 'users', 'departments', 'history', 'folders'));
    }

    /**
     * ประมวลผล Workflow (ส่งต่อ / ปิดเรื่อง) - รองรับหลายผู้รับ (Multi-Select)
     */
    public function process(Request $request, Document $document): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:send,comment,approve,reject,close,receive',
            // เปลี่ยนจาก receiver_id เป็น receiver_ids และต้องเป็น array
            'receiver_type' => 'required_if:action,send|in:user,department',
            'receiver_ids' => 'required_if:action,send|array',
            'note' => 'nullable|string|max:1000',
        ]);

        // [FIX] Security: Check Accessibility
        if (Document::accessibleBy(auth()->user())->where('id', $document->id)->doesntExist()) {
            abort(403, 'คุณไม่มีสิทธิ์ดำเนินการกับเอกสารนี้');
        }

        DB::beginTransaction();

        try {
            // เตรียมข้อมูลพื้นฐาน
            $baseRouteData = [
                'document_id' => $document->id,
                'from_user_id' => auth()->id(),
                'action' => $request->action,
                'note' => $request->note,
                'created_at' => now(),
            ];

            // เก็บรายชื่อคนที่จะได้รับแจ้งเตือน (Notification Targets)
            $targetUsers = collect();

            // กรณีส่งต่อ (Send) - รองรับหลายคน/หลายแผนก
            if ($request->action === 'send') {

                // วนลูปตามรายชื่อที่เลือกมา
                foreach ($request->receiver_ids as $receiverId) {
                    $routeData = $baseRouteData; // copy ข้อมูลพื้นฐาน

                    if ($request->receiver_type === 'user') {
                        // ส่งให้บุคคล
                        $routeData['to_user_id'] = $receiverId;
                        $user = User::find($receiverId);
                        if ($user)
                            $targetUsers->push($user);
                    } else {
                        // ส่งให้หน่วยงาน
                        $routeData['to_department_id'] = $receiverId;
                        // แจ้งเตือนทุกคนในแผนกนั้น
                        $deptUsers = User::where('department_id', $receiverId)->get();
                        $targetUsers = $targetUsers->merge($deptUsers);
                    }

                    // สร้าง Record การส่งทีละรายการ
                    $document->routes()->create($routeData);
                }

                // อัปเดตสถานะเอกสาร
                $document->update(['status' => 'active']);
            }
            // กรณีปิดเรื่อง (Close) - ทำเหมือนเดิม
            elseif ($request->action === 'close') {
                $document->update(['status' => 'closed']);
                // บันทึก Route แค่รายการเดียว
                $document->routes()->create($baseRouteData);

                if ($document->user_id !== auth()->id()) {
                    $targetUsers->push($document->user);
                }
            }
            // กรณีลงรับ (Receive)
            elseif ($request->action === 'receive') {
                $receiveNo = $this->documentNumberService->getNextReceiveNumber(auth()->user()->department_id);

                $routeData = $baseRouteData;
                $routeData['receive_no'] = $receiveNo;
                $routeData['receive_date'] = now();

                // บันทึกว่ารับมาจากใคร (เอา Route ล่าสุดที่ส่งมาหาเรา)
                // แต่ใน baseRouteData เราใส่ from_user_id เป็นตัวเราเอง (คนกดรับ)
                // ซึ่งถูกต้องแล้ว เพราะ Action 'receive' คือ "ฉันได้รับแล้ว"

                $document->routes()->create($routeData);

                // อัปเดตสถานะเอกสารเป็น Received (เฉพาะถ้ามันยังเป็น Active)
                // แต่จริงๆ status ของ Document เป็น Global... ถ้าคนนึงรับ อีกคนยังไม่รับ จะทำไง?
                // Status 'received' อาจจะหมายถึง "มีการรับแล้วอย่างน้อย 1 คน" หรือไม่ก็ไม่ต้องเปลี่ยน Status หลัก
                // แต่ถ้าตาม Flow ปกติ รับแล้วก็คือรับ
                // $document->update(['status' => 'received']); 
            }
            // กรณีอื่นๆ (Comment, Approve)
            else {
                $document->routes()->create($baseRouteData);
            }

            // ส่ง Notification (Unique เพื่อไม่ให้แจ้งเตือนซ้ำคนเดิม)
            $uniqueTargets = $targetUsers->unique('id');
            if ($uniqueTargets->isNotEmpty()) {
                Notification::send($uniqueTargets, new DocumentActionNotification(
                    $document,
                    auth()->user(),
                    $request->action,
                    $request->note
                ));
            }

            // [LOG]
            $receiverCount = is_array($request->receiver_ids) ? count($request->receiver_ids) : 0;
            AuditLogger::log('process', 'document', $document->document_no, "ดำเนินการ: {$request->action} (จำนวนผู้รับ: {$receiverCount})");

            DB::commit();

            return back()->with('success', 'ดำเนินการเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * ยกเลิกการส่งเอกสาร (Cancel Send)
     * เงื่อนไข: 1. ต้องเป็นเจ้าของเอกสาร  2. ยังไม่มีคนกดรับ
     */
    public function cancelSend(Document $document): RedirectResponse
    {
        $user = auth()->user();

        // เงื่อนไข 1: ต้องเป็นผู้สร้างเอกสาร
        if ($document->user_id !== $user->id) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ยกเลิกการส่งเอกสารนี้ เนื่องจากคุณไม่ใช่ผู้สร้างเอกสาร');
        }

        // เงื่อนไข 2: ตรวจสอบว่ายังไม่มีคนกดรับเอกสาร
        // เช็คจาก document_routes ว่ามี action = 'receive' หรือ 'comment' จากผู้อื่น (ไม่ใช่เจ้าของเอกสาร) หรือไม่
        $hasBeenReceived = $document->routes()
            ->where('from_user_id', '!=', $user->id)
            ->whereIn('action', ['receive', 'comment'])
            ->exists();

        if ($hasBeenReceived) {
            return back()->with('error', 'ไม่สามารถยกเลิกได้ เนื่องจากมีผู้รับเอกสารนี้แล้ว');
        }

        // เช็คว่าเอกสารต้องอยู่ในสถานะ active (ส่งแล้ว) เท่านั้น
        if ($document->status !== 'active') {
            return back()->with('error', 'ไม่สามารถยกเลิกได้ เนื่องจากเอกสารไม่ได้อยู่ในสถานะที่ส่งแล้ว');
        }

        DB::beginTransaction();

        try {
            // ลบ Routes ที่เป็น action 'send' ทั้งหมด
            $document->routes()->where('action', 'send')->delete();

            // เปลี่ยนสถานะเป็น cancelled (ยกเลิกการส่ง)
            $document->update(['status' => 'cancelled']);

            // บันทึก Route การยกเลิก
            $document->routes()->create([
                'from_user_id' => $user->id,
                'action' => 'cancel_send',
                'note' => 'ยกเลิกการส่งเอกสาร',
            ]);

            // [LOG]
            AuditLogger::log('cancel_send', 'document', $document->document_no, 'ยกเลิกการส่งเอกสาร: ' . $document->title);

            DB::commit();

            return back()->with('success', 'ยกเลิกการส่งเอกสารเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * ดาวน์โหลดไฟล์แนบ (Secure Download)
     */
    public function download(DocumentAttachment $attachment)
    {
        // [FIX] Security: Check Document Accessibility via Relationship
        $document = $attachment->document;
        if (Document::accessibleBy(auth()->user())->where('id', $document->id)->doesntExist()) {
            abort(403);
        }

        // [LOG]
        AuditLogger::log('download', 'attachment', $attachment->document->document_no, "ดาวน์โหลดไฟล์: {$attachment->file_name}");

        // ตรวจสอบไฟล์ใน Disk 'public'
        if (Storage::disk('public')->exists($attachment->file_path)) {
            return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
        }

        return back()->with('error', 'ไม่พบไฟล์ต้นฉบับ');
    }
}