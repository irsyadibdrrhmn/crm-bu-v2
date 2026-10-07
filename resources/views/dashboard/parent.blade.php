<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded">Portal Orang Tua / Wali</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Transparansi Layanan Pendidikan</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Monitoring Akademik & Layanan Ananda</h1>
            <p class="text-xs text-slate-500 mt-0.5">Informasi presensi real-time, evaluasi hasil belajar, dan kanal aspirasi resmi SMA Binaul Ummah.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- PROFIL SISWA & RINGKASAN AKADEMIK -->
        @forelse ($children as $child)
            @php
                $recentReports = $child->academicReports;
                $avgScore = $recentReports->count() ? round($recentReports->avg('nilai_akhir'), 1) : 0;
                $totalHadir = $child->attendances->where('status', 'hadir')->count();
                $totalHari = $child->attendances->count();
                $hadirPercent = $totalHari > 0 ? round(($totalHadir / $totalHari) * 100) : 100;
            @endphp
            <div class="panel p-6 border-t-4 border-t-indigo-600">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-lg">
                            {{ substr($child->nama_lengkap, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ $child->nama_lengkap }}</h2>
                            <div class="flex flex-wrap items-center gap-2 mt-0.5 text-xs text-slate-500">
                                <span>NIS: <strong class="text-slate-700">{{ $child->nis }}</strong></span>
                                <span>•</span>
                                <span>Kelas: <strong class="text-indigo-600">{{ $child->kelas }}</strong></span>
                                <span>•</span>
                                <span>Tahun Ajaran: {{ $child->tahun_ajaran }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('reports.index') }}" class="btn-light text-xs py-2 px-3">
                            Detail Rapor Nilai
                        </a>
                        <a href="{{ route('tickets.create') }}?student_id={{ $child->id }}" class="btn-indigo text-xs py-2 px-3">
                            + Ajukan Pertanyaan / Aduan
                        </a>
                    </div>
                </div>

                <!-- Metrics Ananda -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Rata-rata Nilai</span>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-2xl font-black text-slate-900">{{ $avgScore }}</span>
                            <span class="text-xs font-semibold {{ $avgScore >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $avgScore >= 75 ? 'Di Atas KKM' : 'Perlu Pendampingan' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Tingkat Presensi</span>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-2xl font-black text-slate-900">{{ $hadirPercent }}%</span>
                            <span class="text-xs text-slate-400">({{ $totalHadir }}/{{ $totalHari }} hari)</span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Mata Pelajaran</span>
                        <div class="mt-1">
                            <span class="text-2xl font-black text-slate-900">{{ $recentReports->count() }}</span>
                            <span class="text-xs text-slate-500">Mata Pelajaran Aktif</span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-xs text-slate-500 uppercase font-semibold">Status Siswa</span>
                        <div class="mt-2">
                            <span class="badge bg-emerald-100 text-emerald-800">Aktif & Terdaftar</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="panel p-8 text-center">
                <p class="text-sm text-slate-500">Data siswa terkait belum terhubung dengan akun Anda. Silakan hubungi tata usaha sekolah.</p>
            </div>
        @endforelse

        <!-- STATUS TIKET PENGADUAN & PENGUMUMAN DUA KOLOM -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Riwayat Pengaduan Wali Siswa -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tiket Layanan & Aspirasi Anda</h3>
                        <p class="text-xs text-slate-500">Pantau proses penanganan pertanyaan/keluhan sekolah.</p>
                    </div>
                    <a href="{{ route('tickets.create') }}" class="btn-indigo text-xs py-1.5 px-3">
                        + Buat Tiket Baru
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
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada tiket pengaduan yang diajukan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Pengumuman Resmi Sekolah -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengumuman & Agenda Sekolah</h3>
                        <p class="text-xs text-slate-500">Informasi resmi dari pihak manajemen SMA Binaul Ummah.</p>
                    </div>
                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">Internal</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($announcements as $ann)
                        <div class="p-3.5 rounded-lg bg-slate-50/70 border border-slate-200/70">
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $ann->tanggal_publish->format('d M Y') }}</span>
                            <h4 class="text-sm font-bold text-slate-900 mt-0.5">{{ $ann->judul }}</h4>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $ann->konten }}</p>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada pengumuman baru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

