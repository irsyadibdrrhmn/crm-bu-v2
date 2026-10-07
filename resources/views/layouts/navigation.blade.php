@php
    $user = auth()->user();
    
    if ($user?->isAdmin()) {
        $navItems = [
            ['label' => 'Analytical Dashboard', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'chart'],
            ['label' => 'Tiket Layanan & Aduan', 'route' => 'tickets.index', 'active' => 'tickets.*', 'icon' => 'ticket'],
            ['label' => 'Data & Performa Siswa', 'route' => 'students.index', 'active' => 'students.*', 'icon' => 'academic'],
            ['label' => 'Presensi Siswa', 'route' => 'attendance.index', 'active' => 'attendance.*', 'icon' => 'calendar'],
            ['label' => 'Manajemen Akun', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'user-group'],
            ['label' => 'Log Komunikasi / Email', 'route' => 'communication-logs.index', 'active' => 'communication-logs.*', 'icon' => 'mail'],
            ['label' => 'Pengumuman Sekolah', 'route' => 'pengumuman.index', 'active' => 'pengumuman.*', 'icon' => 'megaphone'],
        ];
    } elseif ($user?->isParent()) {
        $navItems = [
            ['label' => 'Portal Wali Siswa', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'home'],
            ['label' => 'Layanan & Pengaduan', 'route' => 'tickets.index', 'active' => 'tickets.*', 'icon' => 'ticket'],
            ['label' => 'Nilai & Rapor Ananda', 'route' => 'reports.index', 'active' => 'reports.*', 'icon' => 'academic'],
            ['label' => 'Rekap Presensi Harian', 'route' => 'attendance.index', 'active' => 'attendance.*', 'icon' => 'calendar'],
        ];
    } else { // Student
        $navItems = [
            ['label' => 'Portal Siswa', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'home'],
            ['label' => 'Suara Siswa & Aspirasi', 'route' => 'tickets.index', 'active' => 'tickets.*', 'icon' => 'ticket'],
            ['label' => 'Evaluasi Belajar & Nilai', 'route' => 'reports.index', 'active' => 'reports.*', 'icon' => 'academic'],
            ['label' => 'Presensi Saya', 'route' => 'attendance.index', 'active' => 'attendance.*', 'icon' => 'calendar'],
        ];
    }

    $renderIcon = function ($icon) {
        $paths = [
            'chart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
            'ticket' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />',
            'academic' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5" />',
            'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
            'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
            'megaphone' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />',
            'user-group' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
            'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        ];

        return '<svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">'.$paths[$icon].'</svg>';
    };
@endphp

<!-- Desktop Sidebar (Navy / Slate 900) -->
<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 border-r border-slate-800 bg-slate-900 px-5 py-6 text-white shadow-xl lg:flex lg:flex-col">
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center font-bold text-white shadow-md">
                BU
            </div>
            <div>
                <span class="text-[10px] font-bold tracking-widest uppercase text-indigo-400">Analytical CRM</span>
                <h1 class="text-base font-bold text-white leading-tight">SMA Binaul Ummah</h1>
            </div>
        </div>
        <p class="mt-3 text-xs text-slate-400 leading-relaxed border-t border-slate-800/80 pt-3">
            Sistem analitik mutu layanan, aduan terpadu, dan transparansi akademik siswa.
        </p>
    </div>

    <!-- Navigation links -->
    <nav class="space-y-1.5 flex-1">
        @foreach ($navItems as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">
                {!! $renderIcon($item['icon']) !!}
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- User Profile & Sign Out Footer -->
    <div class="pt-4 border-t border-slate-800">
        <div class="rounded-xl border border-slate-800 bg-slate-800/50 p-3.5">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ $user->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-400 capitalize">
                        Role: {{ $user->role === 'parent' ? 'Orang Tua' : ($user->role === 'student' ? 'Siswa' : 'Admin & Manajemen') }}
                    </p>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('profile.edit') }}" class="text-center w-1/2 rounded-md bg-slate-800 hover:bg-slate-700 py-1.5 text-xs font-medium text-slate-300">
                    Profil
                </a>
                <form class="w-1/2" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full rounded-md bg-rose-950/60 hover:bg-rose-900 border border-rose-800/40 py-1.5 text-xs font-medium text-rose-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Navigation Bar -->
<nav class="sticky top-0 z-40 border-b border-slate-200 bg-slate-900 px-4 py-3 text-white lg:hidden">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-xs">BU</div>
            <p class="font-bold text-sm">SMA Binaul Ummah</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-xs font-semibold text-rose-300 hover:text-white">Logout</button>
        </form>
    </div>
    <div class="mt-3 flex overflow-x-auto gap-2 pb-1 scrollbar-none">
        @foreach ($navItems as $item)
            @php($active = request()->routeIs($item['active']))
            <a href="{{ route($item['route']) }}" class="whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-medium {{ $active ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</nav>
