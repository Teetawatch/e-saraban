<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ระบบสารบรรณอิเล็กทรอนิกส์ รร.พธ.พธ.ทร.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Scripts & Styles -->
    <!-- Scripts & Styles (Manual Load for Shared Host) -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-BILHC7h_.css') }}">
    <script type="module" src="{{ asset('build/assets/app-kGY04szw.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-700 relative">
    <!-- Global Background Texture -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-50/50 via-white to-white"></div>
        <div class="absolute inset-0 opacity-[0.015]"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');">
        </div>
    </div>

    <div x-data="{ sidebarOpen: false }" class="relative flex h-screen overflow-hidden z-10">

        <!-- Sidebar (Light Theme) -->
        <aside
            class="absolute inset-y-0 left-0 z-50 w-64 transition-transform duration-300 transform bg-white border-r border-slate-200 lg:static lg:translate-x-0 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full'">
            <!-- Logo -->
            <div
                class="flex items-center justify-center h-16 bg-white border-b border-slate-100 text-slate-800 shadow-sm shrink-0 px-2">
                <div class="flex items-center justify-center gap-2 w-full">
                    <div
                        class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center shadow-md shadow-brand-200 shrink-0">
                        <i class="fa-solid fa-folder-open text-xs"></i>
                    </div>
                    <span class="text-brand-700 font-bold text-sm truncate">ระบบสารบรรณอิเล็กทรอนิกส์</span>
                </div>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-2">เมนูหลัก</p>

                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                    <i
                        class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                    <span>หน้าหลัก</span>
                </a>

                <!-- Inbox -->
                <a href="{{ route('documents.index', ['tab' => 'inbox']) }}"
                    class="{{ (request()->routeIs('documents.index') && request('tab') != 'outbox') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                    <i
                        class="fa-solid fa-inbox w-5 text-center {{ (request()->routeIs('documents.index') && request('tab') != 'outbox') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                    <span>หนังสือเข้า</span>
                </a>

                <!-- Outbox -->
                <a href="{{ route('documents.index', ['tab' => 'outbox']) }}"
                    class="{{ (request()->routeIs('documents.index') && request('tab') == 'outbox') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                    <i
                        class="fa-solid fa-paper-plane w-5 text-center {{ (request()->routeIs('documents.index') && request('tab') == 'outbox') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                    <span>หนังสือออก</span>
                </a>

                <!-- E-Filing (Cabinet) -->
                <a href="{{ route('folders.index') }}"
                    class="{{ request()->routeIs('folders.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                    <i
                        class="fa-solid fa-folder-tree w-5 text-center {{ request()->routeIs('folders.*') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                    <span>ตู้เอกสารออนไลน์</span>
                </a>

                <!-- Advanced Search -->
                <a href="{{ route('documents.search') }}"
                    class="{{ request()->routeIs('documents.search') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                    <i
                        class="fa-solid fa-magnifying-glass w-5 text-center {{ request()->routeIs('documents.search') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                    <span>ค้นหาขั้นสูง</span>
                </a>

                <!-- Admin Menu -->
                @if(Auth::user() && Auth::user()->hasRole('admin'))
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">จัดการระบบ</p>

                    <a href="{{ route('admin.reports.index') }}"
                        class="{{ request()->routeIs('admin.reports.index') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                        <i
                            class="fa-solid fa-chart-line w-5 text-center {{ request()->routeIs('admin.reports.index') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                        <span>รายงานสรุป</span>
                    </a>

                    <a href="{{ route('admin.departments.index') }}"
                        class="{{ request()->routeIs('admin.departments.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                        <i
                            class="fa-solid fa-building w-5 text-center {{ request()->routeIs('admin.departments.*') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                        <span>จัดการหน่วยงาน</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                        <i
                            class="fa-solid fa-users w-5 text-center {{ request()->routeIs('admin.users.*') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                        <span>จัดการผู้ใช้งาน</span>
                    </a>

                    <a href="{{ route('admin.document_types.index') }}"
                        class="{{ request()->routeIs('admin.document_types.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                        <i
                            class="fa-solid fa-sliders w-5 text-center {{ request()->routeIs('admin.document_types.*') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                        <span>ตั้งค่าประเภทเอกสาร</span>
                    </a>

                    <a href="{{ route('admin.sequences.index') }}"
                        class="{{ request()->routeIs('admin.sequences.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                        <i
                            class="fa-solid fa-arrow-down-1-9 w-5 text-center {{ request()->routeIs('admin.sequences.*') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                        <span>ตั้งค่าเลขรับ - ส่ง</span>
                    </a>

                    <a href="{{ route('admin.audit_logs.index') }}"
                        class="{{ request()->routeIs('admin.audit_logs.index') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-brand-700' }} flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all duration-200 group">
                        <i
                            class="fa-solid fa-shoe-prints w-5 text-center {{ request()->routeIs('admin.audit_logs.index') ? 'text-brand-600' : 'text-slate-400 group-hover:text-brand-600' }}"></i>
                        <span>Audit Logs</span>
                    </a>
                @endif
            </nav>

            <!-- User Profile Mini (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-white flex items-center justify-center text-primary-600 text-xs font-bold overflow-hidden border border-slate-200 shadow-sm">
                        @if(Auth::user()->avatar)
                            <img src="{{ route('storage.file', ['path' => Auth::user()->avatar]) }}"
                                class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" style="display: none;">
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <header
                class="flex items-center justify-between h-16 px-6 bg-white/60 backdrop-blur-xl border-b border-white/60 sticky top-0 z-30 transition-all duration-300 shadow-sm shadow-brand-900/5">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="text-slate-500 hover:text-slate-700 focus:outline-none lg:hidden transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg font-semibold text-slate-800 hidden sm:block">ระบบสารบรรณอิเล็กทรอนิกส์</h2>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">

                    <!-- Notification Bell -->
                    <div class="relative" x-data="{ 
                        notifOpen: false, 
                        unreadCount: {{ auth()->user()->unreadNotifications->count() }},
                        checkNotifications() {
                            fetch('{{ route('notifications.check') }}')
                                .then(response => response.json())
                                .then(data => {
                                    this.unreadCount = data.unread_count;
                                });
                        }
                    }" x-init="setInterval(() => checkNotifications(), 60000)">
                        <button @click="notifOpen = !notifOpen"
                            class="relative p-2 text-slate-400 hover:text-brand-600 hover:bg-white/80 rounded-full transition-all focus:outline-none">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <template x-if="unreadCount > 0">
                                <span
                                    class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white ring-2 ring-white animate-pulse"
                                    x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                            </template>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="notifOpen" @click.away="notifOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50 origin-top-right"
                            style="display: none;">

                            <div
                                class="px-4 py-3 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                                <h3 class="text-sm font-bold text-slate-800">การแจ้งเตือน</h3>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.readAll') }}"
                                        class="text-xs font-medium text-primary-600 hover:text-primary-700 hover:underline">อ่านทั้งหมด</a>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                @forelse(auth()->user()->notifications as $notification)
                                                                <a href="{{ route('notifications.read', $notification->id) }}"
                                                                    class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/30' }}">
                                                                    <div class="flex gap-3">
                                                                        <div class="mt-1">
                                                                            <div
                                                                                class="w-8 h-8 rounded-full {{ $notification->read_at ? 'bg-slate-100 text-slate-400' : 'bg-primary-100 text-primary-600' }} flex items-center justify-center text-xs">
                                                                                @if($notification->data['action'] == 'send') <i
                                                                                    class="fa-solid fa-paper-plane"></i>
                                                                                @elseif($notification->data['action'] == 'close') <i
                                                                                    class="fa-solid fa-check"></i>
                                                                                @else <i class="fa-solid fa-bell"></i> @endif
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm font-semibold text-slate-800 line-clamp-1">
                                                                                {{ $notification->data['title'] }}</p>
                                                                            <p class="text-xs text-slate-500 mt-0.5">
                                                                                <span
                                                                                    class="font-medium text-slate-700">{{ $notification->data['sender_name'] }}</span>
                                                                                {{ match ($notification->data['action']) {
                                        'send' => 'ได้ส่งเอกสารถึงคุณ',
                                        'close' => 'ได้ปิดเรื่อง/อนุมัติแล้ว',
                                        default => 'ได้ดำเนินการ'
                                    } }}
                                                                            </p>
                                                                            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                                                                <i class="fa-regular fa-clock"></i>
                                                                                {{ $notification->created_at->diffForHumans() }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                @empty
                                    <div class="px-4 py-12 text-center text-slate-400">
                                        <div
                                            class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-regular fa-bell-slash text-xl opacity-50"></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500">ไม่มีการแจ้งเตือน</p>
                                        <p class="text-xs text-slate-400 mt-1">คุณอ่านครบทุกรายการแล้ว</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 focus:outline-none p-1 pr-3 rounded-full hover:bg-slate-100 transition-colors border border-transparent hover:border-slate-200">
                            <div
                                class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 shadow-sm bg-white">
                                @if(Auth::user()->avatar)
                                    <img src="{{ route('storage.file', ['path' => Auth::user()->avatar]) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-primary-100 flex items-center justify-center text-primary-600 text-xs font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div class="hidden md:block text-left">
                                <span
                                    class="block text-sm font-medium text-slate-700 leading-tight">{{ Auth::user()->name }}</span>
                                <span
                                    class="text-[10px] text-slate-400 block leading-tight">{{ Auth::user()->roles->pluck('label')->first() ?? 'ผู้ใช้งานทั่วไป' }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 border border-slate-100 z-50 origin-top-right"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-slate-50 mb-2">
                                <p class="text-sm font-bold text-slate-800">บัญชีผู้ใช้</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">
                                <i class="fa-regular fa-user w-4 text-center"></i> ข้อมูลส่วนตัว
                            </a>

                            <div class="border-t border-slate-50 my-2"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> ออกจากระบบ
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-transparent p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>