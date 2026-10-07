<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Portal Siswa</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">SMA Binaul Ummah</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Akses rapor hasil belajar, ajukan aspirasi perbaikan fasilitas, dan informasi kegiatan sekolah.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if ($student)
            @php
                $reports = $student->academicReports;
                $avg = $reports->count() ? round($reports->avg('nilai_akhir'), 1) : 0;
                $attendances = $student->attendances;
                $hadirCount = $attendances->where('status', 'hadir')->count();
                $totalDays = $attendances->count();
                $rate = $totalDays > 0 ? round(($hadirCount / $totalDays) * 100) : 100;
            @endphp
            <!-- Ringkasan Kartu Siswa -->
            <div class="panel p-6 border-l-4 border-l-indigo-600">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $student->nama_lengkap }}</h2>
                        <p class="text-xs text-slate-500">NIS: {{ $student->nis }} • Kelas: {{ $student->kelas }} • Jurusan: {{ $student->jurusan }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('reports.index') }}" class="btn-indigo text-xs py-2 px-3">
                            Lihat Rapor Belajar
                        </a>
                        <a href="{{ route('tickets.create') }}" class="btn-light text-xs py-2 px-3">
                            + Kirim Aspirasi / Keluhan
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Rata-Rata Nilai</span>
                        <div class="mt-1 text-2xl font-black text-slate-900">{{ $avg }}</div>
                        <span class="text-[11px] text-emerald-600 font-medium">Semester Ganjil</span>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Kehadiran</span>
                        <div class="mt-1 text-2xl font-black text-slate-900">{{ $rate }}%</div>
                        <span class="text-[11px] text-slate-400">Total {{ $hadirCount }} hari</span>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Status Presensi</span>
                        <div class="mt-2">
                            <span class="badge bg-emerald-100 text-emerald-800">Tertib & Disiplin</span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Aspirasi Saya</span>
                        <div class="mt-1 text-2xl font-black text-slate-900">{{ $myTickets->count() }}</div>
                        <span class="text-[11px] text-slate-500">Tiket Terkirim</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Suara Siswa & Pengumuman -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Suara Siswa: Tiket Aspirasi -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Suara Siswa & Aspirasi Anda</h3>
                        <p class="text-xs text-slate-500">Aspirasi seputar fasilitas, KBM, dan ekstrakurikuler.</p>
                    </div>
                    <a href="{{ route('tickets.create') }}" class="btn-indigo text-xs py-1.5 px-3">
                        + Buat Tiket
                    </a>
                </div>
                <div class="mt-4 divide-y divide-slate-100">
                    @forelse ($myTickets as $ticket)
                        <div class="py-3 flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-indigo-600">{{ $ticket->ticket_number }}</span>
                                    <span class="badge badge-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</span>
                                </div>
                                <h4 class="mt-1 text-sm font-semibold text-slate-800">{{ $ticket->judul }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $ticket->deskripsi }}</p>
                            </div>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn-light text-xs py-1 px-2.5 shrink-0">
                                Buka
                            </a>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada aspirasi atau keluhan yang kamu kirimkan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Pengumuman Sekolah -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengumuman & Agenda Siswa</h3>
                        <p class="text-xs text-slate-500">Pemberitahuan dari kesiswaan dan sekolah.</p>
                    </div>
                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">Info</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($announcements as $ann)
                        <div class="p-3.5 rounded-lg bg-slate-50/70 border border-slate-200/70">
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $ann->tanggal_publish->format('d M Y') }}</span>
                            <h4 class="text-sm font-bold text-slate-900 mt-0.5">{{ $ann->judul }}</h4>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $ann->konten }}</p>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada pengumuman baru untuk siswa.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

