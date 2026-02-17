@extends('layouts.app')

@section('content')
    <div class="w-full px-4 sm:px-6 lg:px-8 pb-10">

        <!-- Page Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    @if($tab == 'outbox')
                        <div
                            class="w-10 h-10 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-paper-plane text-lg"></i>
                        </div>
                        <div>
                            <span>หนังสือออก</span>
                            <span class="block text-sm font-normal text-slate-500 mt-0.5">เอกสารที่ส่งจาก"หน่วยงานของคุณ"</span>
                        </div>
                    @else
                        <div
                            class="w-10 h-10 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-inbox text-lg"></i>
                        </div>
                        <div>
                            <span>หนังสือเข้า</span>
                            <span class="block text-sm font-normal text-slate-500 mt-0.5">เอกสารที่ส่งถึง"หน่วยงานของคุณ"</span>
                        </div>
                    @endif
                </h1>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('documents.create', ['direction' => 'inbound']) }}"
                    class="inline-flex items-center justify-center bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-inbox mr-2"></i> ลงทะเบียนหนังสือเข้า
                </a>
                <a href="{{ route('documents.create', ['direction' => 'outbound']) }}"
                    class="inline-flex items-center justify-center bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-brand-500/30 transition-all transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-paper-plane mr-2"></i> ลงทะเบียนหนังสือออก
                </a>
            </div>
        </div>

        <!-- Main Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <!-- Toolbar: Tabs & Search -->
            <div
                class="flex flex-col md:flex-row justify-between items-center border-b border-slate-100 bg-white p-4 gap-4">

                <!-- Modern Tabs (Segmented Control) -->
                <div class="flex p-1.5 bg-slate-100 rounded-xl w-full md:w-auto">
                    <a href="{{ route('documents.index', ['tab' => 'inbox']) }}"
                        class="flex-1 md:flex-none px-6 py-2 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2
                       {{ $tab == 'inbox' ? 'bg-white text-slate-800 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700' }}">
                        <i class="fa-solid fa-inbox {{ $tab == 'inbox' ? 'text-brand-600' : '' }}"></i> หนังสือเข้า
                    </a>
                    <a href="{{ route('documents.index', ['tab' => 'outbox']) }}"
                        class="flex-1 md:flex-none px-6 py-2 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2
                       {{ $tab == 'outbox' ? 'bg-white text-slate-800 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700' }}">
                        <i class="fa-solid fa-paper-plane {{ $tab == 'outbox' ? 'text-brand-600' : '' }}"></i> หนังสือออก
                    </a>
                </div>

                <!-- Search Box -->
                <form action="{{ route('documents.index') }}" method="GET" class="w-full md:w-auto relative group">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 group-focus-within:text-brand-600 transition-colors">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 block w-full md:w-80 pl-10 p-2.5 transition-all shadow-sm group-hover:bg-white"
                        placeholder="ค้นหาเลขที่, เรื่อง...">
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold w-32">เลขที่ / วันที่</th>
                            <th scope="col" class="px-6 py-4 font-semibold">เรื่อง</th>
                            <th scope="col" class="px-6 py-4 font-semibold">ประเภท / ความด่วน</th>
                            <th scope="col" class="px-6 py-4 font-semibold">ผู้ดำเนินการ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right">สถานะ</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($documents as $doc)
                            <tr class="group hover:bg-slate-50 transition-colors cursor-pointer {{ $doc->status === 'cancelled' ? 'opacity-60' : '' }}"
                                onclick="window.location='{{ route('documents.show', $doc) }}'">

                                <!-- Col 1: Document No & Date -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        @php
                                            $receiveNo = null;
                                            if ($tab == 'inbox') {
                                                // Find receipt for my department
                                                $myDeptId = auth()->user()->department_id;
                                                $receiveRoute = $doc->routes->filter(function ($r) use ($myDeptId) {
                                                    return $r->action == 'receive'
                                                        && $r->receive_no
                                                        && $r->fromUser
                                                        && $r->fromUser->department_id == $myDeptId;
                                                })->sortByDesc('id')->first();

                                                if ($receiveRoute) {
                                                    $receiveNo = $receiveRoute->receive_no;
                                                }
                                            }
                                        @endphp

                                        @if($receiveNo)
                                            <span
                                                class="font-mono font-bold text-emerald-600 text-sm group-hover:text-emerald-700 transition-colors">
                                                <i class="fa-solid fa-check-to-slot mr-1 text-xs"></i>รับ-{{ $receiveNo }}
                                            </span>
                                            <span class="text-xs text-slate-400 mt-0.5">
                                                (เดิม: {{ $doc->document_no }})
                                            </span>
                                        @else
                                            <span
                                                class="font-mono font-bold text-sm transition-colors {{ $doc->status === 'cancelled' ? 'text-red-400 line-through' : 'text-slate-700 group-hover:text-brand-700' }}">
                                                {{ $doc->document_no }}
                                            </span>
                                        @endif

                                        <span class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                            <i class="fa-regular fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($doc->document_date)->toThaiDate() }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Col 2: Title & Attachments -->
                                <td class="px-6 py-4">
                                    @if($doc->urgency->name !== 'ปกติ')
                                        <div class="mb-1">
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold border shadow-sm"
                                                style="color: {{ $doc->urgency->color }}; border-color: {{ $doc->urgency->color }}30; background-color: {{ $doc->urgency->color }}08;">
                                                <i class="fa-solid fa-bolt"></i> {{ $doc->urgency->name }}
                                            </span>
                                        </div>
                                    @endif
                                    <div
                                        class="font-semibold text-base mb-1 line-clamp-2 transition-colors {{ $doc->status === 'cancelled' ? 'text-red-400 line-through' : 'text-slate-800 group-hover:text-brand-700' }}">
                                        {{ $doc->title }}
                                    </div>
                                    @if($doc->attachments->count() > 0)
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-medium border border-slate-200">
                                            <i class="fa-solid fa-paperclip"></i> {{ $doc->attachments->count() }} ไฟล์แนบ
                                        </div>
                                    @endif
                                </td>

                                <!-- Col 3: Type & Urgency -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-2">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            {{ $doc->type->name }}
                                        </span>


                                    </div>
                                </td>

                                <!-- Col 4: User Avatar -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div
                                                class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200 shadow-sm overflow-hidden">
                                                @if($doc->user->avatar)
                                                    <img src="{{ route('storage.file', ['path' => $doc->user->avatar]) }}"
                                                        alt="{{ $doc->user->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($doc->user->name, 0, 1) }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-slate-700">{{ $doc->user->name }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $doc->department->name }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Col 5: Status Badge -->
                                <td class="px-6 py-4 text-right">
                                    @php
                                        // คำนวณสถานะจริง: กรณี outbox + active → ตรวจสอบว่ารับครบหมดแล้วหรือยัง
                                        $effectiveStatus = $doc->status;

                                        if ($tab == 'outbox' && $doc->status === 'active') {
                                            $sendRoutes = $doc->routes->where('action', 'send');
                                            $receiveRoutes = $doc->routes->where('action', 'receive');

                                            if ($sendRoutes->isNotEmpty()) {
                                                // นับผู้รับที่ต้องรับ (แยก user / department)
                                                $sentToUsers = $sendRoutes->whereNotNull('to_user_id')->pluck('to_user_id')->unique();
                                                $sentToDepts = $sendRoutes->whereNotNull('to_department_id')->pluck('to_department_id')->unique();

                                                // นับที่รับแล้ว
                                                $receivedByUsers = $receiveRoutes->pluck('from_user_id')->unique();
                                                $receivedByDepts = $receiveRoutes->map(fn($r) => $r->fromUser?->department_id)->filter()->unique();

                                                $allUsersReceived = $sentToUsers->isEmpty() || $sentToUsers->diff($receivedByUsers)->isEmpty();
                                                $allDeptsReceived = $sentToDepts->isEmpty() || $sentToDepts->diff($receivedByDepts)->isEmpty();

                                                if ($allUsersReceived && $allDeptsReceived) {
                                                    $effectiveStatus = 'closed';
                                                }
                                            }
                                        }

                                        $statusClass = match ($effectiveStatus) {
                                            'draft' => 'bg-slate-100 text-slate-600 border border-slate-200',
                                            'active' => 'bg-brand-50 text-brand-600 border border-brand-100',
                                            'closed' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                            'cancelled' => 'bg-red-50 text-red-600 border border-red-200',
                                            default => 'bg-slate-100 text-slate-600'
                                        };
                                        $statusText = match ($effectiveStatus) {
                                            'draft' => 'ฉบับร่าง',
                                            'active' => 'กำลังดำเนินการ',
                                            'closed' => 'ดำเนินการเสร็จสิ้น',
                                            'cancelled' => 'ยกเลิกการส่ง',
                                            default => $effectiveStatus
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        @if($effectiveStatus == 'closed')
                                            <i class="fa-solid fa-circle-check"></i>
                                        @elseif($effectiveStatus == 'active')
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                                        @elseif($effectiveStatus == 'cancelled')
                                            <i class="fa-solid fa-ban text-red-500"></i>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        @endif
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <!-- Col 6: Actions / Arrow Icon -->
                                <td class="px-6 py-4 text-center">
                                    @if($tab == 'outbox')
                                        @php
                                            $canCancelSendInList = $doc->status === 'active'
                                                && $doc->user_id === auth()->id()
                                                && !$doc->routes->where('action', 'receive')
                                                    ->where('from_user_id', '!=', auth()->id())->count()
                                                && !$doc->routes->where('action', 'comment')
                                                    ->where('from_user_id', '!=', auth()->id())->count();
                                        @endphp
                                        @if($canCancelSendInList)
                                            <form action="{{ route('documents.cancelSend', $doc) }}" method="POST"
                                                onclick="event.stopPropagation()"
                                                onsubmit="return confirm('ยกเลิกการส่งเอกสาร {{ $doc->document_no }} ?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all"
                                                    title="ยกเลิกการส่ง">
                                                    <i class="fa-solid fa-rotate-left text-[10px]"></i> ยกเลิก
                                                </button>
                                            </form>
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-slate-300 group-hover:text-brand-600 group-hover:bg-brand-50 transition-all">
                                                <i class="fa-solid fa-chevron-right text-xs"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-slate-300 group-hover:text-brand-600 group-hover:bg-brand-50 transition-all">
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="flex flex-col items-center justify-center py-16 text-center">
                                        <div
                                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                            <i class="fa-regular fa-folder-open text-4xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-semibold text-lg">ไม่พบเอกสาร</h3>
                                        <p class="text-slate-500 text-sm mt-1 max-w-xs mx-auto">
                                            ยังไม่มีเอกสารในหมวดหมู่นี้ หรือลองค้นหาด้วยคำค้นอื่น
                                        </p>
                                        @if($tab != 'inbox')
                                            <a href="{{ route('documents.create') }}"
                                                class="mt-4 inline-flex items-center text-brand-600 hover:text-brand-700 text-sm font-medium hover:underline">
                                                <i class="fa-solid fa-plus mr-1"></i> สร้างเอกสารใหม่
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($documents->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection