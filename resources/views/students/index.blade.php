<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Database Siswa</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Siswa Aktif</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Manajemen & Performa Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">Database siswa terdaftar SMA Binaul Ummah beserta evaluasi nilai dan rekap pengaduan.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter bar -->
        <div class="panel p-4">
            <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="label-modern">Cari Nama / NIS</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIS siswa..." class="input-modern">
                </div>
                <div>
                    <label class="label-modern">Filter Kelas</label>
                    <select name="kelas" class="input-modern">
                        <option value="">Semua Rombel Kelas</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full text-xs">Cari Data</button>
                    <a href="{{ route('students.index') }}" class="btn-light text-xs">Reset</a>
                </div>
            </form>
        </div>

        <div class="panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>NIS / NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas & Jurusan</th>
                            <th>Orang Tua / Wali</th>
                            <th>No. HP Kontak</th>
                            <th>Rata-Rata Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($students as $student)
                            @php
                                $avg = $student->academicReports->count() ? round($student->academicReports->avg('nilai_akhir'), 1) : 0;
                            @endphp
                            <tr>
                                <td class="font-mono text-xs font-semibold text-slate-900">{{ $student->nis }}</td>
                                <td>
                                    <div class="text-xs font-bold text-slate-900">{{ $student->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-400">JK: {{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </td>
                                <td>
                                    <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">{{ $student->kelas }}</span>
                                </td>
                                <td>
                                    <div class="text-xs text-slate-800">{{ $student->parent ? $student->parent->name : 'Wali Murid' }}</div>
                                </td>
                                <td class="text-xs font-mono text-slate-600">{{ $student->no_hp_ortu ?? '-' }}</td>
                                <td>
                                    <span class="text-xs font-black {{ $avg >= 75 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $avg }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('students.show', $student) }}" class="btn-light !py-1 !px-2.5 !text-xs font-semibold">
                                        Performa Siswa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-xs text-slate-400">Data siswa tidak ditemukan.</td>
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

