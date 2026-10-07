<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Rapor Belajar</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Evaluasi Real-Time</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Capaian Akademik & Rapor Nilai</h1>
            <p class="text-xs text-slate-500 mt-0.5">Transparansi penilaian harian, tugas, PTS, dan semester SMA Binaul Ummah.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if ($student)
            <div class="panel p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $student->nama_lengkap }}</h2>
                        <p class="text-xs text-slate-500">NIS: {{ $student->nis }} • Kelas: {{ $student->kelas }} ({{ $student->jurusan }})</p>
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
                                <th>Catatan Guru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($student->academicReports as $report)
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
                                    <td colspan="8" class="text-center py-6 text-xs text-slate-400">Belum ada data nilai semester ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="panel p-6 text-center text-xs text-slate-400">Data profil siswa belum terhubung.</div>
        @endif
    </div>
</x-app-layout>

