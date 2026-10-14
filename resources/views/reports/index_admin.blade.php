<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Manajemen Kurikulum</span>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Rekap Nilai Siswa</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Data Capaian Akademik Seluruh Siswa</h1>
                <p class="text-xs text-slate-500 mt-0.5">Analisis ketercapaian KKM dan evaluasi berkala per rombongan belajar SMA Binaul Ummah.</p>
            </div>
            <div>
                <a href="{{ route('reports.create') }}" class="btn-indigo text-xs">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    + Input Nilai Akademik Siswa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter bar -->
        <div class="panel p-4">
            <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="label-modern">Cari Nama / NIS</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIS siswa..." class="input-modern">
                </div>
                <div>
                    <label class="label-modern">Filter Rombel Kelas</label>
                    <select name="kelas" class="input-modern">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full text-xs">Filter Nilai</button>
                    <a href="{{ route('reports.index') }}" class="btn-light text-xs">Reset</a>
                </div>
            </form>
        </div>
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

