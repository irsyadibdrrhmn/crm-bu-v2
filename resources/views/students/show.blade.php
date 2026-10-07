<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('students.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Database Siswa</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">{{ $student->nama_lengkap }}</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Profil & Analisis Performa Siswa</h1>
            </div>
            <a href="{{ route('students.index') }}" class="btn-light text-xs">← Kembali ke Database</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Kartu Identitas Siswa -->
        <div class="panel p-6 border-l-4 border-l-indigo-600">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $student->nama_lengkap }}</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        NIS: <strong>{{ $student->nis }}</strong> • NISN: <strong>{{ $student->nisn }}</strong> • Kelas: <strong class="text-indigo-600">{{ $student->kelas }}</strong> ({{ $student->jurusan }})
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge bg-emerald-100 text-emerald-800">Status Aktif</span>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 text-xs">
                <div>
                    <span class="text-slate-400 block font-semibold uppercase">Orang Tua / Wali</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $student->parent ? $student->parent->name : '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold uppercase">Kontak Telepon</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $student->no_hp_ortu ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold uppercase">Total Nilai Terinput</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $student->academicReports->count() }} Mata Pelajaran</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold uppercase">Tiket Aduan Terkait</span>
                    <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $student->complaintTickets->count() }} Tiket</span>
                </div>
            </div>
        </div>

        <!-- Rapor Nilai & Presensi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Nilai Siswa -->
            <div class="panel p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 pb-3 border-b border-slate-100">
                    Nilai Akademik Semester Ganjil
                </h3>
                <div class="overflow-x-auto mt-3">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>KKM</th>
                                <th>Nilai Akhir</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($student->academicReports as $r)
                                <tr>
                                    <td class="text-xs font-semibold text-slate-900">{{ $r->mata_pelajaran }}</td>
                                    <td class="text-xs text-slate-500">{{ $r->kkm }}</td>
                                    <td class="text-xs font-bold {{ $r->nilai_akhir >= $r->kkm ? 'text-emerald-700' : 'text-rose-700 font-black' }}">{{ $r->nilai_akhir }}</td>
                                    <td><span class="badge {{ $r->grade === 'A' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">{{ $r->grade }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-xs text-slate-400">Belum ada data nilai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tiket Layanan Terkait Siswa Ini -->
            <div class="panel p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 pb-3 border-b border-slate-100">
                    Riwayat Tiket Aduan / Aspirasi Siswa
                </h3>
                <div class="mt-3 divide-y divide-slate-100">
                    @forelse ($student->complaintTickets as $tk)
                        <div class="py-3 flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-indigo-600">{{ $tk->ticket_number }}</span>
                                    <span class="badge badge-{{ $tk->status }}">{{ ucfirst($tk->status) }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 mt-1">{{ $tk->judul }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $tk->created_at->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('tickets.show', $tk) }}" class="btn-light text-xs !py-1 !px-2">Buka</a>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Tidak ada riwayat aduan terkait siswa ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

