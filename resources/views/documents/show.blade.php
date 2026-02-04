@extends('layouts.app')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-8 pb-20" x-data="{ 
            modalOpen: false, 
            actionType: 'send', 
            receiverType: 'user',

            // Preview Modal Logic
            previewOpen: false,
            previewSrc: '',
            previewType: '',
            previewName: '',
            openPreview(url, type, name) {
                this.previewSrc = url;
                this.previewType = type;
                this.previewName = name;
                this.previewOpen = true;
            }
        }">

    @php
        // Logic: ตรวจสอบว่าผู้รับทุกคนในรายการ Send ได้รับเรื่องแล้วหรือไม่ (Auto-Close Visual)
        $allReceived = false;
        $activeRoutes = $document->routes;
        
        // ต้องเป็นสถานะ Active และมีการส่งออกไปแล้ว
        if ($document->status == 'active' && $activeRoutes->where('action', 'send')->count() > 0) {
            $pendingSends = 0;
            
            foreach ($activeRoutes as $idx => $route) {
                if ($route->action == 'send') {
                    $isAck = false;
                    $targetUserId = $route->to_user_id;
                    $targetDeptId = $route->to_department_id;
                    
                    // Lookahead: เช็คว่ามี Route ต่อจากนี้ที่ตอบรับหรือไม่
                    $laterRoutes = $activeRoutes->slice($idx + 1);
                    foreach ($laterRoutes as $lr) {
                        // กรณีส่งให้บุคคล -> คนนั้นทำรายการอะไรก็ได้กลับมา (รับ/ส่งต่อ/ปิด/คอมเมนต์)
                        if ($targetUserId && $lr->from_user_id == $targetUserId) {
                            $isAck = true; break;
                        }
                        // กรณีส่งให้หน่วยงาน -> ใครก็ได้ในหน่วยงานนั้นทำรายการกลับมา
                        if ($targetDeptId && $lr->fromUser && $lr->fromUser->department_id == $targetDeptId) {
                            $isAck = true; break;
                        }
                    }
                    
                    if (!$isAck) {
                        $pendingSends++;
                    }
                }
            }
            
            // ถ้าไม่มี pending sends ถือว่าครบแล้ว
            if ($pendingSends == 0) {
                $allReceived = true;
            }
        }
        
        // Override Status สำหรับการแสดงผลเท่านั้น
        $runtimeStatus = $allReceived ? 'closed' : $document->status;
    @endphp

        <!-- Top Navigation -->
        <nav class="flex items-center text-sm text-slate-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition-colors"><i
                    class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-xs mx-3 text-slate-300"></i>
            <a href="{{ route('documents.index') }}" class="hover:text-brand-600 transition-colors">หนังสือราชการ</a>
            <i class="fa-solid fa-chevron-right text-xs mx-3 text-slate-300"></i>
            <span class="text-slate-800 font-medium tracking-wide">รายละเอียด</span>
        </nav>

        <!-- Success Alert -->
        @if(session('success'))
            <div
                class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 shadow-sm animate-fade-in-down">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">

            <!-- Left Panel: Document Details (8 Cols) -->
            <div class="xl:col-span-8 space-y-6">

                <!-- 1. Document Header Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                    <!-- Status Strip Top -->
                    <div
                        class="h-2 w-full {{ match ($runtimeStatus) { 'draft' => 'bg-slate-400', 'active' => 'bg-brand-500', 'closed' => 'bg-emerald-500', default => 'bg-slate-400'} }}">
                    </div>

                    <div class="p-6 sm:p-8">
                        <!-- Header Meta -->
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-3">
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-mono font-bold tracking-wider border border-slate-200">
                                    {{ $document->document_no }}
                                </span>
                                <span class="px-2 py-1 rounded text-xs font-bold border"
                                    style="color: {{ $document->urgency->color }}; border-color: {{ $document->urgency->color }}40; background-color: {{ $document->urgency->color }}10;">
                                    <i class="fa-solid fa-bolt mr-1"></i>{{ $document->urgency->name }}
                                </span>
                                @if($document->confidential->name !== 'ปกติ')
                                    <span
                                        class="px-2 py-1 rounded text-xs font-bold border border-red-200 bg-red-50 text-red-600">
                                        <i class="fa-solid fa-shield-halved mr-1"></i>{{ $document->confidential->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium
                                    {{ match ($runtimeStatus) {
        'draft' => 'bg-slate-100 text-slate-600',
        'active' => 'bg-brand-50 text-brand-700 border border-brand-100',
        'closed' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
        default => 'bg-slate-100 text-slate-600'
    } }}">
                                    <span
                                        class="w-2 h-2 rounded-full {{ match ($runtimeStatus) { 'draft' => 'bg-slate-400', 'active' => 'bg-brand-500', 'closed' => 'bg-emerald-500', default => 'bg-slate-400'} }}"></span>
                                    {{ match ($runtimeStatus) {
        'draft' => 'ฉบับร่าง',
        'active' => 'กำลังดำเนินการ',
        'closed' => 'ดำเนินการเสร็จสิ้น',
        default => $runtimeStatus
    } }}
                                </span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 leading-snug mb-6">
                            {{ $document->title }}
                        </h1>

                        <!-- Sender Profile Box -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-white border border-slate-200 flex items-center justify-center text-brand-600 font-bold text-lg shadow-sm overflow-hidden">
                                @if($document->user->avatar)
                                    <img src="{{ route('storage.file', ['path' => $document->user->avatar]) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ substr($document->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $document->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $document->department->name }} • เจ้าของเรื่อง</p>
                            </div>
                            <div class="ml-auto text-right hidden sm:block">
                                <p class="text-xs text-slate-400">สร้างเมื่อ</p>
                                <p class="text-sm font-medium text-slate-600">
                                    {{ \Carbon\Carbon::parse($document->created_at)->toThaiDateTime() }} น.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Meta Grid -->
                    <div
                        class="bg-slate-50/50 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 divide-x divide-slate-100">
                        <div class="p-4 text-center">
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">ประเภท</p>
                            <p class="font-medium text-slate-700">{{ $document->type->name }}</p>
                        </div>
                        <div class="p-4 text-center">
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">ลงวันที่</p>
                            <p class="font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($document->document_date)->toThaiDate() }}</p>
                        </div>
                        <div class="p-4 text-center">
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">อัปโหลดเมื่อ</p>
                            <p class="font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($document->created_at)->locale('th')->diffForHumans() }}</p>
                        </div>
                        <div class="p-4 text-center">
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">ไฟล์แนบ</p>
                            <p class="font-medium text-brand-600">{{ $document->attachments->count() }} ไฟล์</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Files Section -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fa-regular fa-folder-open text-slate-400"></i> เอกสารแนบ
                    </h3>

                    @if($document->attachments->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($document->attachments as $file)
                                <div
                                    class="group relative bg-white border border-slate-200 rounded-xl p-4 hover:shadow-md hover:border-brand-300 transition-all duration-300 flex items-start gap-4">
                                    <!-- File Icon -->
                                    <div
                                        class="w-12 h-12 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-2xl shrink-0 group-hover:scale-105 transition-transform">
                                        @if(in_array(strtolower($file->file_type), ['jpg', 'png', 'jpeg']))
                                            <i class="fa-regular fa-image text-purple-500"></i>
                                        @elseif(in_array(strtolower($file->file_type), ['pdf']))
                                            <i class="fa-regular fa-file-pdf text-red-500"></i>
                                        @elseif(in_array(strtolower($file->file_type), ['doc', 'docx']))
                                            <i class="fa-regular fa-file-word text-blue-500"></i>
                                        @elseif(in_array(strtolower($file->file_type), ['xls', 'xlsx']))
                                            <i class="fa-regular fa-file-excel text-green-500"></i>
                                        @else
                                            <i class="fa-regular fa-file text-slate-400"></i>
                                        @endif
                                    </div>

                                    <!-- File Details -->
                                    <div class="flex-1 min-w-0">
                                        <a href="#"
                                            @click.prevent="openPreview('{{ route('storage.file', ['path' => $file->file_path]) }}', '{{ $file->file_type }}', '{{ $file->file_name }}')"
                                            class="block focus:outline-none">
                                            <p
                                                class="text-sm font-bold text-slate-700 truncate group-hover:text-brand-600 transition-colors">
                                                {{ $file->file_name }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ strtoupper($file->file_type) }} •
                                                {{ number_format($file->file_size / 1024, 2) }} KB</p>
                                        </a>
                                    </div>

                                    <!-- Preview Button -->
                                    <button type="button"
                                        @click="openPreview('{{ route('storage.file', ['path' => $file->file_path]) }}', '{{ $file->file_type }}', '{{ $file->file_name }}')"
                                        class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors"
                                        title="ดูตัวอย่าง">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <!-- Download Button -->
                                    <a href="{{ route('documents.download', $file->id) }}"
                                        class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors"
                                        title="ดาวน์โหลด">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 border-dashed rounded-xl p-10 text-center">
                            <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-2"></i>
                            <p class="text-slate-500 text-sm">ไม่มีไฟล์แนบในเอกสารนี้</p>
                        </div>
                    @endif
                </div>

                <!-- 3. History of Actions -->
                <div class="mt-6">
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                            <h3 class="font-bold text-slate-800">ประวัติการดำเนินการ</h3>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach($history as $item)
                                                    <div class="p-4 flex items-start gap-3 hover:bg-slate-50 transition-colors">
                                                        <!-- User Avatar -->
                                                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 overflow-hidden border-2 
                                                            {{ match ($item->type) {
                                    'view' => 'border-slate-200 bg-slate-100',
                                    'action' => match ($item->action) {
                                            'created' => 'border-emerald-200 bg-emerald-50',
                                            'send' => 'border-brand-200 bg-brand-50',
                                            'close' => 'border-slate-600 bg-slate-700',
                                            'comment' => 'border-orange-200 bg-orange-50',
                                            default => 'border-slate-200 bg-slate-100'
                                        },
                                    default => 'border-slate-200 bg-slate-100'
                                } }}">
                                                            @if($item->user && $item->user->avatar)
                                                                <img src="{{ route('storage.file', ['path' => $item->user->avatar]) }}"
                                                                    class="w-full h-full object-cover">
                                                            @else
                                                                                        <span class="text-xs font-bold {{ match ($item->type) {
                                                                    'view' => 'text-slate-500',
                                                                    'action' => match ($item->action) {
                                                                            'created' => 'text-emerald-600',
                                                                            'send' => 'text-brand-600',
                                                                            'close' => 'text-white',
                                                                            'comment' => 'text-orange-600',
                                                                            default => 'text-slate-500'
                                                                        },
                                                                    default => 'text-slate-500'
                                                                } }}">{{ substr($item->user->name ?? '?', 0, 1) }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex justify-between items-start">
                                                                <p class="text-sm font-bold text-slate-700">
                                                                    {{ $item->user->name ?? 'Unknown' }}
                                                                </p>
                                                                <span class="text-xs text-slate-400">{{ $item->created_at->toThaiTime() }} น.</span>
                                                            </div>
                                                            <p class="text-sm text-slate-600 mt-1">
                                                                {{ match ($item->type) {
                                    'view' => 'เปิดอ่านเอกสาร',
                                    'action' => match ($item->action) {
                                            'created' => 'สร้างเอกสาร',
                                            'send' => 'ส่งต่อเอกสาร',
                                            'close' => 'ปิดเรื่อง',
                                            'comment' => 'ลงรับเรื่อง',
                                            'receive' => 'ลงรับเอกสาร',
                                            default => $item->action
                                        },
                                    default => $item->type
                                } }}
                                                            </p>
                                                            @if($item->note)
                                                                <p class="text-xs text-slate-500 mt-1 italic">"{{ $item->note }}"</p>
                                                            @endif
                                                        </div>
                                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Actions & Timeline (4 Cols) -->
            <div class="xl:col-span-4 space-y-6">

                <!-- Actions Card (Sticky on Desktop) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    @if($runtimeStatus !== 'closed')
                        <h3 class="font-bold text-slate-800 mb-4">การดำเนินการ</h3>
                        <div class="space-y-3">
                            <button @click="actionType = 'send'; modalOpen = true"
                                class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-medium transition-colors flex items-center justify-center gap-2 shadow-sm shadow-brand-200">
                                <i class="fa-solid fa-paper-plane"></i> ส่งต่อเอกสาร
                            </button>

                            @php
                                // เช็คว่า user คนนี้เคยทำรายการ 'receive' หรือ 'comment' ไปแล้วหรือยัง
                                $hasReceived = $document->routes->where('from_user_id', auth()->id())
                                    ->whereIn('action', ['receive', 'comment'])
                                    ->isNotEmpty();
                            @endphp

                            @if($hasReceived)
                                <button disabled
                                    class="w-full py-2.5 px-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl font-medium flex items-center justify-center gap-2 cursor-not-allowed opacity-80">
                                    <i class="fa-solid fa-circle-check"></i> ลงรับแล้ว
                                </button>
                            @else
                                <button @click="actionType = 'receive'; modalOpen = true"
                                    class="w-full py-2.5 px-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-pen"></i> ลงรับ / บันทึกความเห็น
                                </button>
                            @endif

                            <button @click="actionType = 'close'; modalOpen = true"
                                class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> ปิดเรื่อง / อนุมัติ
                            </button>
                        </div>
                    @else
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-check text-3xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">ดำเนินการเสร็จสิ้น</h3>
                            <p class="text-slate-500 text-sm mt-2">เอกสารนี้ถูกปิดเรื่องเรียบร้อยแล้ว<br>
                                @if($allReceived && $document->status != 'closed')
                                <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-1 inline-block">ครบองค์ประกอบการรับ</span>
                                @endif
                            </p>

                            <!-- Filing Section -->
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                @if($document->folder_id)
                                    <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 text-left relative group">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-indigo-500 shadow-sm shrink-0">
                                                <i class="fa-solid fa-folder-closed"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider mb-0.5">
                                                    ถูกจัดเก็บในแฟ้ม</p>
                                                <a href="{{ route('folders.show', $document->folder_id) }}"
                                                    class="text-sm font-bold text-indigo-900 hover:underline line-clamp-1">{{ $document->folder->name }}</a>
                                            </div>
                                        </div>
                                        <button @click="$dispatch('open-filing-modal')"
                                            class="absolute top-2 right-2 p-1.5 text-indigo-400 hover:text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors"
                                            title="ย้ายแฟ้ม">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <button @click="$dispatch('open-filing-modal')"
                                        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors flex items-center justify-center gap-2 shadow-md shadow-indigo-200 animate-pulse-slow">
                                        <i class="fa-solid fa-folder-plus"></i> จัดเก็บเข้าตู้เอกสาร
                                    </button>
                                    <p class="text-xs text-slate-400 mt-2">ย้ายเอกสารไปเก็บในแฟ้มเพื่อการค้นหาที่ง่ายขึ้น</p>
                                @endif
                            </div>

                            <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100 text-left">
                                <p class="text-xs text-slate-400 mb-1">ปิดเรื่องเมื่อ</p>
                                <p class="text-sm font-medium text-slate-700">{{ $document->updated_at->toThaiDateTime() }} น.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Timeline (Modified UI: Scrollable Area) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col max-h-[600px]">

                    <!-- Fixed Header (ส่วนหัวไม่เลื่อนตาม) -->
                    <div class="p-6 border-b border-slate-100 bg-white rounded-t-2xl z-10 shadow-sm">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-brand-500"></i> เส้นทางเอกสาร
                            <span
                                class="bg-slate-100 text-slate-500 text-xs px-2 py-0.5 rounded-full ml-auto">{{ count($document->routes) }}
                                รายการ</span>
                        </h3>
                    </div>

                    <!-- Scrollable Content (ส่วนเนื้อหาเลื่อนได้) -->
                    <div class="p-6 overflow-y-auto custom-scrollbar">
                        <div class="relative pl-3">
                            <!-- Line -->
                            <div class="absolute left-[15px] top-3 bottom-5 w-[2px] bg-slate-100"></div>

                            <div class="space-y-8">
                                @foreach($document->routes as $index => $route)
                                                            @php
                                                                // Logic เช็คสถานะการรับเรื่อง (Pending)
                                                                $isLast = $loop->last;
                                                                $lastRouteTime = $document->routes->last()->created_at;
                                                                // เช็คว่าเวลาใกล้เคียงกับรายการสุดท้ายหรือไม่ (ไม่เกิน 2 วินาที) ถือเป็น Batch เดียวกัน
                                                                $isLatestBatch = $route->created_at->diffInSeconds($lastRouteTime) < 2;

                                                                $isPending = $isLatestBatch && $route->action == 'send' && $runtimeStatus != 'closed';
                                                            @endphp

                                                            <div class="relative flex gap-4">
                                                                <!-- Dot -->
                                                                <div class="relative z-10 mt-1 w-8 h-8 rounded-full flex items-center justify-center shrink-0 border-2 border-white shadow-sm ring-2 ring-slate-50
                                                                    {{ match ($route->action) {
                                        'created' => 'bg-emerald-500 text-white',
                                        'send' => 'bg-brand-500 text-white',
                                        'close' => 'bg-slate-700 text-white',
                                        'comment' => 'bg-orange-400 text-white',
                                        default => 'bg-slate-400 text-white'
                                    } }}">
                                                                    <i class="{{ match ($route->action) {
                                        'created' => 'fa-solid fa-plus',
                                        'send' => 'fa-solid fa-share',
                                        'close' => 'fa-solid fa-flag-checkered',
                                        'comment' => 'fa-solid fa-file-import',
                                        default => 'fa-solid fa-circle'
                                    } }} text-[10px]"></i>
                                                                </div>

                                                                <!-- Content -->
                                                                <div class="flex-1 bg-slate-50 rounded-xl p-4 border border-slate-100 relative">
                                                                    <!-- Triangle Arrow -->
                                                                    <div
                                                                        class="absolute top-4 -left-1.5 w-3 h-3 bg-slate-50 border-l border-b border-slate-100 transform rotate-45">
                                                                    </div>

                                                                    <div class="flex justify-between items-start mb-2 relative z-10">
                                                                        <span class="text-sm font-bold text-slate-800">
                                                                            {{ match ($route->action) {
                                        'created' => 'สร้างหนังสือ',
                                        'send' => 'ส่งต่อ / สั่งการ',
                                        'close' => 'ปิดเรื่อง / อนุมัติ',
                                        'comment' => 'ลงรับ / รับทราบ',
                                        'receive' => 'ลงรับเอกสาร',
                                        default => $route->action
                                    } }}
                                                                        </span>
                                                                        <span class="text-[10px] text-slate-400">
                                                                            {{ $route->created_at->toThaiTime() }} น.
                                                                        </span>
                                                                    </div>

                                                                    <div class="flex items-center gap-2 mb-2">
                                                                        <div class="w-5 h-5 rounded-full bg-slate-200 overflow-hidden flex-shrink-0">
                                                                            @if($route->fromUser->avatar)
                                                                                <img src="{{ route('storage.file', ['path' => $route->fromUser->avatar]) }}"
                                                                                    class="w-full h-full object-cover">
                                                                            @else
                                                                                <div
                                                                                    class="w-full h-full flex items-center justify-center text-[8px] font-bold text-slate-500">
                                                                                    {{ substr($route->fromUser->name, 0, 1) }}</div>
                                                                            @endif
                                                                        </div>
                                                                        <p class="text-xs font-medium text-slate-700">{{ $route->fromUser->name }}</p>
                                                                    </div>

                                                                    <!-- ส่วนแสดงผู้รับและสถานะการรับ (Logic Lookahead) -->
                                                                    @if($route->action == 'send')
                                                                        @php
                                                                            $receiverInfo = null;
                                                                            // Lookahead Logic: หาว่ามีคนรับช่วงต่อหรือยัง
                                                                            $nextRoutes = $document->routes->slice($index + 1);
                                                                            foreach ($nextRoutes as $nextRoute) {
                                                                                // กรณีส่งให้บุคคล: คนนั้นตอบกลับมาแล้ว
                                                                                if ($route->to_user_id && $nextRoute->from_user_id == $route->to_user_id) {
                                                                                    $receiverInfo = $nextRoute->fromUser;
                                                                                    break;
                                                                                }
                                                                                // กรณีส่งให้หน่วยงาน: ใครก็ได้ในหน่วยงานนั้นตอบกลับมา
                                                                                if ($route->to_department_id && $nextRoute->fromUser && $nextRoute->fromUser->department_id == $route->to_department_id) {
                                                                                    $receiverInfo = $nextRoute->fromUser;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        @if(($route->to_user_id || $route->to_department_id))
                                                                            <div class="mt-3 pt-3 border-t border-slate-200">
                                                                                <p class="text-xs text-slate-400 mb-1">ส่งถึง:</p>
                                                                                <!-- [MODIFIED] Changed to vertical layout -->
                                                                                <div
                                                                                    class="flex flex-col items-start gap-2 bg-white p-3 rounded border border-slate-200 w-full">

                                                                                    <!-- ชื่อผู้รับปลายทาง -->
                                                                                    <div class="flex items-center gap-2 w-full">
                                                                                        @if($route->toUser)
                                                                                            <div
                                                                                                class="w-6 h-6 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs shrink-0">
                                                                                                <i class="fa-solid fa-user"></i></div>
                                                                                            <span
                                                                                                class="text-xs font-bold text-slate-700 break-words">{{ $route->toUser->name }}</span>
                                                                                        @elseif($route->toDepartment)
                                                                                            <div
                                                                                                class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs shrink-0">
                                                                                                <i class="fa-solid fa-building"></i></div>
                                                                                            <span
                                                                                                class="text-xs font-bold text-slate-700 break-words">{{ $route->toDepartment->name }}</span>
                                                                                        @endif
                                                                                    </div>

                                                                                    <!-- สถานะการรับ (แสดงชื่อคนรับถ้ามี) -->
                                                                                    <div class="w-full">
                                                                                        @if($receiverInfo)
                                                                                            <span
                                                                                                class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100 w-fit"
                                                                                                title="ดำเนินการต่อโดย {{ $receiverInfo->name }}">
                                                                                                <i class="fa-solid fa-circle-check"></i> รับเรื่องแล้ว
                                                                                            </span>
                                                                                        @elseif($isPending)
                                                                                            <span
                                                                                                class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-medium bg-orange-50 text-orange-600 border border-orange-100 w-fit">
                                                                                                <span class="relative flex h-2 w-2">
                                                                                                    <span
                                                                                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                                                                    <span
                                                                                                        class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                                                                                </span>
                                                                                                รอรับเรื่อง
                                                                                            </span>
                                                                                        @else
                                                                                            <!-- กรณีเป็นรายการเก่าแล้ว แต่ไม่มีคนรับ (อาจจะตกหล่น หรือส่งต่อข้ามไปเลย) -->
                                                                                            <span
                                                                                                class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200 w-fit">
                                                                                                <i class="fa-solid fa-check"></i> ส่งแล้ว
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if($route->note)
                                                                        <p
                                                                            class="text-sm text-slate-600 italic bg-white p-2 rounded border border-slate-100 mt-2">
                                                                            "{{ $route->note }}"
                                                                        </p>
                                                                    @endif

                                                                    <p class="text-[10px] text-slate-300 mt-2 text-right">
                                                                        {{ \Carbon\Carbon::parse($route->created_at)->toThaiDate() }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Modal (Multi-Select Support) -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>

            <div class="flex items-center justify-center min-h-screen p-4">
                <div
                    class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800"
                            x-text="actionType === 'send' ? 'ส่งต่อเอกสาร' : (actionType === 'close' ? 'ยืนยันการปิดเรื่อง' : 'ลงรับ / บันทึกความเห็น')">
                        </h3>
                        <button @click="modalOpen = false"
                            class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('documents.process', $document) }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-5">
                            <input type="hidden" name="action" x-model="actionType">

                            <!-- Multi-Select List -->
                            <div x-show="actionType === 'send'">
                                <label class="block mb-3 text-sm font-semibold text-slate-700">ส่งถึง (เลือกได้มากกว่า
                                    1)</label>

                                <div class="flex p-1 bg-slate-100 rounded-lg mb-4">
                                    <label
                                        class="flex-1 text-center cursor-pointer py-2 rounded-md text-sm font-medium transition-all"
                                        :class="receiverType === 'user' ? 'bg-white text-brand-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                                        <input type="radio" name="receiver_type" value="user" x-model="receiverType"
                                            class="hidden">
                                        <i class="fa-solid fa-user mr-1"></i> บุคคล
                                    </label>
                                    <label
                                        class="flex-1 text-center cursor-pointer py-2 rounded-md text-sm font-medium transition-all"
                                        :class="receiverType === 'department' ? 'bg-white text-brand-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                                        <input type="radio" name="receiver_type" value="department" x-model="receiverType"
                                            class="hidden">
                                        <i class="fa-solid fa-building mr-1"></i> หน่วยงาน
                                    </label>
                                </div>

                                <div class="border border-slate-200 rounded-xl overflow-hidden">
                                    <div
                                        class="bg-slate-50 px-3 py-2 border-b border-slate-200 text-xs text-slate-500 font-medium">
                                        เลือกผู้รับ
                                    </div>
                                    <div class="max-h-48 overflow-y-auto p-2 space-y-1 bg-white">

                                        <div x-show="receiverType === 'user'">
                                            @foreach($users as $u)
                                                <label
                                                    class="flex items-center p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors group">
                                                    <input type="checkbox" name="receiver_ids[]" value="{{ $u->id }}"
                                                        class="w-4 h-4 text-brand-600 border-slate-300 rounded focus:ring-brand-500">
                                                    <div class="ml-3 flex-1">
                                                        <p
                                                            class="text-sm font-medium text-slate-700 group-hover:text-brand-700">
                                                            {{ $u->name }}</p>
                                                        <p class="text-xs text-slate-400">{{ $u->roles->first()->label ?? '-' }}
                                                        </p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div x-show="receiverType === 'department'" style="display: none;">
                                            @foreach($departments as $dept)
                                                <label
                                                    class="flex items-center p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors group">
                                                    <input type="checkbox" name="receiver_ids[]" value="{{ $dept->id }}"
                                                        class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500"
                                                        :disabled="receiverType !== 'department'">
                                                    <div class="ml-3 flex-1">
                                                        <p
                                                            class="text-sm font-medium text-slate-700 group-hover:text-primary-700">
                                                            {{ $dept->name }}</p>
                                                        <p class="text-xs text-slate-400">รหัส: {{ $dept->code ?? '-' }}</p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2 text-right">สามารถติ๊กเลือกได้หลายรายการ</p>
                            </div>

                            <!-- Note -->
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700"
                                    x-text="actionType === 'send' ? 'ข้อความถึงผู้รับ' : (actionType === 'close' ? 'ผลการพิจารณา / บันทึกท้ายเรื่อง' : 'บันทึกการรับ / ความเห็น')"></label>
                                <textarea name="note" rows="3"
                                    class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl p-3 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="รายละเอียด..."></textarea>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="modalOpen = false"
                                class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm rounded-lg hover:bg-slate-50">ยกเลิก</button>
                            <button type="submit"
                                class="px-5 py-2.5 text-white text-sm rounded-lg shadow-md transition-colors flex items-center gap-2"
                                :class="actionType === 'send' ? 'bg-brand-600 hover:bg-brand-700' : 'bg-slate-800 hover:bg-slate-900'">
                                <span
                                    x-text="actionType === 'send' ? 'ยืนยันการส่ง' : (actionType === 'close' ? 'ยืนยันปิดเรื่อง' : 'ยืนยันลงรับ')"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
        <div x-show="previewOpen" class="fixed inset-0 z-[60] overflow-hidden" style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="previewOpen = false"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div
                    class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white z-10">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2"><i
                                class="fa-regular fa-eye text-brand-500"></i> <span x-text="previewName"
                                class="truncate max-w-md"></span></h3>
                        <div class="flex items-center gap-2">
                            <a :href="previewSrc" target="_blank"
                                class="text-sm text-brand-600 hover:bg-brand-50 px-3 py-1.5 rounded-lg"><i
                                    class="fa-solid fa-arrow-up-right-from-square mr-1"></i> เปิดหน้าต่างใหม่</a>
                            <button @click="previewOpen = false"
                                class="text-slate-400 hover:bg-slate-100 w-8 h-8 rounded-full"><i
                                    class="fa-solid fa-xmark text-xl"></i></button>
                        </div>
                    </div>
                    <div class="flex-1 bg-slate-100 overflow-hidden relative flex items-center justify-center">
                        <template x-if="['pdf'].includes(previewType.toLowerCase())"><iframe :src="previewSrc"
                                class="w-full h-full border-none"></iframe></template>
                        <template x-if="['jpg', 'jpeg', 'png'].includes(previewType.toLowerCase())">
                            <div class="w-full h-full overflow-auto flex items-center justify-center p-4"><img
                                    :src="previewSrc" class="max-w-full max-h-full object-contain shadow-lg"></div>
                        </template>
                        <template x-if="!['pdf', 'jpg', 'jpeg', 'png'].includes(previewType.toLowerCase())">
                            <div class="text-center p-10"><i
                                    class="fa-regular fa-file-lines text-4xl text-slate-400 mb-4"></i>
                                <h3 class="text-lg font-bold text-slate-700">ไม่สามารถแสดงตัวอย่าง</h3><a :href="previewSrc"
                                    class="inline-block mt-6 px-6 py-2.5 bg-brand-600 text-white rounded-xl">ดาวน์โหลดไฟล์</a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- E-Filing Modal -->
        <div x-data="{ filingModalOpen: false }" @open-filing-modal.window="filingModalOpen = true">
            <div x-show="filingModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                    @click="filingModalOpen = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div
                        class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-indigo-50/50">
                            <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                                <i class="fa-solid fa-folder-plus text-indigo-600"></i> จัดเก็บเข้าแฟ้ม
                            </h3>
                            <button @click="filingModalOpen = false"
                                class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <form action="{{ route('documents.file', $document) }}" method="POST">
                            @csrf
                            <div class="p-6">
                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">เลือกแฟ้มเอกสาร</label>
                                    @if($folders->count() > 0)
                                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                            @foreach($folders as $folder)
                                                <label
                                                    class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                                                    <input type="radio" name="folder_id" value="{{ $folder->id }}"
                                                        class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                                        required {{ $document->folder_id == $folder->id ? 'checked' : '' }}>
                                                    <div class="ml-3 flex-1">
                                                        <div class="flex justify-between items-center">
                                                            <span
                                                                class="font-bold text-slate-700 group-hover:text-indigo-700">{{ $folder->name }}</span>
                                                            <span
                                                                class="text-[10px] bg-white border border-slate-200 px-1.5 py-0.5 rounded text-slate-500">{{ $folder->year }}</span>
                                                        </div>
                                                        <p class="text-xs text-slate-500 line-clamp-1">{{ $folder->description }}
                                                        </p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div
                                            class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                            <p class="text-slate-500 text-sm mb-2">ยังไม่มีแฟ้มเอกสาร</p>
                                            <a href="{{ route('folders.index') }}"
                                                class="text-indigo-600 text-sm font-bold hover:underline">สร้างแฟ้มใหม่</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                                <button type="button" @click="filingModalOpen = false"
                                    class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm rounded-lg hover:bg-slate-50">ยกเลิก</button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg shadow-md transition-colors flex items-center gap-2"
                                    {{ $folders->count() == 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-save"></i> บันทึก
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection