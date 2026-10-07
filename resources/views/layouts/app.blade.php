<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Analytical CRM Layanan Siswa & Ortu') }} - SMA Binaul Ummah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="h-full antialiased font-sans bg-slate-50 text-slate-800">
    <div class="app-shell min-h-screen">
        @include('layouts.navigation')
        
        <main class="lg:pl-72 transition-all">
            @isset($header)
                <header class="topbar">
                    <div class="px-6 py-4 lg:px-10 flex items-center justify-between">
                        {{ $header }}
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                                OLAP Real-Time Online
                            </span>
                        </div>
                    </div>
                </header>
            @endisset
            
            <section class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8 max-w-7xl mx-auto">
                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 flex items-center gap-3 shadow-xs">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-900 flex items-center gap-3 shadow-xs">
                        <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </section>
        </main>
    </div>
</body>

</html>
