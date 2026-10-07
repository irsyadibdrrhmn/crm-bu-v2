<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Manajemen Kurikulum</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Rekap Nilai Siswa</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Data Capaian Akademik Seluruh Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">Analisis ketercapaian KKM dan evaluasi berkala per rombongan belajar SMA Binaul Ummah.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Siswa & NIS</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Rata-Rata Nilai</th>
                            <th>Status Kelulusan KKM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($students as $st)
                            @php
                                $reports = $st->academicReports;
                                $avg = $reports->count() ? round($reports->avg('nilai_akhir'), 1) : 0;
                                $underKKM = $reports->where('nilai_akhir', '<', 75)->count();
                            @endphp
                            <tr>
                                <td>
                                    <div class="text-xs font-bold text-slate-900">{{ $st->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-400">NIS: {{ $st->nis }}</div>
                                </td>
                                <td>
                                    <span class="text-xs font-semibold text-slate-700">{{ $st->kelas }}</span>
                                </td>
                                <td class="text-xs text-slate-600">
                                    {{ $reports->count() }} Mapel
                                </td>
                                <td>
                                    <span class="text-xs font-black {{ $avg >= 75 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $avg }}</span>
                                </td>
                                <td>
                                    @if ($underKKM > 0)
                                        <span class="badge bg-rose-50 text-rose-700 border border-rose-200">
                                            {{ $underKKM }} Mapel &lt; KKM
                                        </span>
                                    @else
                                        <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Tuntas Seluruh Mapel
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('students.show', $st) }}" class="btn-light !py-1 !px-2.5 !text-xs font-semibold">
                                        Detail Siswa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-xs text-slate-400">Tidak ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

