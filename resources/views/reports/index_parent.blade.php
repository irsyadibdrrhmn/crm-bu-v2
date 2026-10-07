<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded">Rapor Belajar Ananda</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Evaluasi Berkala</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Rapor & Evaluasi Akademik Real-Time</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pantau nilai tugas, ujian tengah semester, dan catatan bimbingan guru secara transparan.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @forelse ($children as $child)
            <div class="panel p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $child->nama_lengkap }}</h2>
                        <p class="text-xs text-slate-500">NIS: {{ $child->nis }} • Kelas: {{ $child->kelas }} ({{ $child->jurusan }})</p>
                    </div>
                    <span class="badge bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Semester Ganjil - TA 2025/2026
                    </span>
                </div>

                <div class="overflow-x-auto mt-4">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>KKM</th>
                                <th>Tugas</th>
                                <th>UTS/PTS</th>
                                <th>UAS/PAS</th>
                                <th>Nilai Akhir</th>
                                <th>Predikat</th>
                                <th>Catatan Guru Pengampu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($child->academicReports as $report)
                                <tr>
                                    <td class="font-semibold text-slate-900 text-xs">{{ $report->mata_pelajaran }}</td>
                                    <td class="text-xs text-slate-500">{{ $report->kkm }}</td>
                                    <td class="text-xs text-slate-700">{{ $report->nilai_tugas }}</td>
                                    <td class="text-xs text-slate-700">{{ $report->nilai_uts }}</td>
                                    <td class="text-xs text-slate-700">{{ $report->nilai_uas }}</td>
                                    <td class="text-xs font-bold {{ $report->nilai_akhir >= $report->kkm ? 'text-emerald-700' : 'text-rose-700 font-extrabold' }}">
                                        {{ $report->nilai_akhir }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $report->grade === 'A' ? 'bg-emerald-100 text-emerald-800' : ($report->grade === 'B' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $report->grade }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-slate-500 italic max-w-xs">{{ $report->catatan_guru ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-6 text-xs text-slate-400">Belum ada nilai terinput.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="panel p-6 text-center text-xs text-slate-400">Belum ada siswa terhubung dengan akun orang tua ini.</div>
        @endforelse
    </div>
</x-app-layout>

