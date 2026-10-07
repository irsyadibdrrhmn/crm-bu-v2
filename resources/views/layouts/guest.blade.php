<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Analytical CRM') }} - SMA Binaul Ummah</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans text-slate-800 antialiased bg-slate-100 flex items-center justify-center p-4">
        <div class="w-full sm:max-w-md">
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center font-bold text-white shadow-md">
                        BU
                    </div>
                    <span class="text-lg font-bold text-slate-900">SMA Binaul Ummah</span>
                </a>
            </div>

            <div class="panel p-6 sm:p-8 shadow-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
