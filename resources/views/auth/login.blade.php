<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk ke Portal Layanan & Analitik - SMA Binaul Ummah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased font-sans bg-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="grid w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl border border-slate-200/80 md:grid-cols-2">
        <!-- Panel Kiri: Identitas Sekolah & Penjelasan Sistem -->
        <div class="relative bg-slate-900 p-8 sm:p-12 text-white flex flex-col justify-between overflow-hidden">
            <!-- Background Glow Decor -->
            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-indigo-600/30 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-sky-600/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div>
                        <span class="text-[11px] font-bold tracking-widest uppercase text-indigo-400">Analytical CRM</span>
                        <h1 class="text-xl font-extrabold text-white leading-tight">SMA Binaul Ummah</h1>
                    </div>
                </div>

                <div class="mt-10 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-snug">
                        Sistem Informasi Mutu Layanan & Transparansi Akademik
                    </h2>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Platform terintegrasi untuk penanganan aduan, monitoring capaian rapor, kehadiran harian real-time, dan komunikasi sekolah.
                    </p>
                </div>
            </div>

            <!-- Quick Info Cards -->
            <div class="relative z-10 mt-10 space-y-3 pt-6 border-t border-slate-800">
                <div class="flex items-center gap-3 text-xs text-slate-300">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-500/20 text-indigo-300 font-bold">✓</span>
                    <span>Monitoring SLA penyelesaian aduan & aspirasi fasilitas</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-300">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-emerald-500/20 text-emerald-300 font-bold">✓</span>
                    <span>Transparansi rapor berkala & rekap absensi ananda</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-300">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-amber-500/20 text-amber-300 font-bold">✓</span>
                    <span>Pusat informasi & agenda resmi SMA Binaul Ummah</span>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Form Login -->
        <div class="bg-white p-8 sm:p-12 flex flex-col justify-center">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-slate-900">Selamat Datang</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan masukkan email dan kata sandi akun Anda untuk melanjutkan.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-emerald-50 p-3 text-xs font-semibold text-emerald-800 border border-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="label-modern">Alamat Email Terdaftar</label>
                    <input id="email" class="input-modern" type="email" name="email" value="{{ old('email') }}" placeholder="nama@domain.sch.id" required autofocus autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="label-modern !mb-0">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-medium text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <input id="password" class="input-modern" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="btn-indigo w-full py-2.5 text-sm font-semibold shadow-md mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Akun Uji Coba Demo (Klik untuk Isi Cepat):</span>
                <div class="flex flex-wrap gap-1.5" x-data="{
                    fill(email) {
                        document.getElementById('email').value = email;
                        document.getElementById('password').value = 'password';
                    }
                }">
                    <button type="button" @click="fill('admin@sma-binaulummah.sch.id')" class="text-[11px] px-2 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-700 rounded border border-slate-200 transition font-medium">
                        🔑 Admin
                    </button>
                    <button type="button" @click="fill('guru@sma-binaulummah.sch.id')" class="text-[11px] px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded border border-emerald-200 transition font-medium">
                        👨‍🏫 Guru / Wali Kelas
                    </button>
                    <button type="button" @click="fill('bk@sma-binaulummah.sch.id')" class="text-[11px] px-2 py-1 bg-purple-50 hover:bg-purple-100 text-purple-800 rounded border border-purple-200 transition font-medium">
                        🧠 Guru BP/BK
                    </button>
                    <button type="button" @click="fill('bambang.wali@gmail.com')" class="text-[11px] px-2 py-1 bg-sky-50 hover:bg-sky-100 text-sky-800 rounded border border-sky-200 transition font-medium">
                        👨‍👩‍👦 Orang Tua
                    </button>
                    <button type="button" @click="fill('raditya.arya@siswa.sma-binaulummah.sch.id')" class="text-[11px] px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded border border-amber-200 transition font-medium">
                        🎓 Siswa
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 mt-1.5">Kata sandi default semua akun demo: <code class="font-mono bg-slate-100 px-1 rounded">password</code></p>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Belum memiliki akun wali atau siswa?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                        Registrasi Terverifikasi
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
