<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Kedisiplinan & Kesiswaan</span>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Log Presensi</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Monitoring Presensi Seluruh Siswa</h1>
                <p class="text-xs text-slate-500 mt-0.5">Audit log kedatangan harian gerbang sekolah SMA Binaul Ummah.</p>
            </div>
            <div>
                <a href="{{ route('attendance.create') }}" class="btn-indigo text-xs">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    + Input Presensi Harian Kelas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Bar -->
        <div class="panel p-4">
            <form method="GET" action="{{ route('attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="label-modern">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="input-modern">
                </div>
                <div>
                    <label class="label-modern">Filter Rombel Kelas</label>
                    <select name="kelas" class="input-modern">
                        <option value="">Semua Rombel</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label-modern">Status Kehadiran</label>
                    <select name="status" class="input-modern">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="alpha" {{ request('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full text-xs">Filter</button>
                    <a href="{{ route('attendance.index') }}" class="btn-light text-xs">Reset</a>
                </div>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Siswa & NIS</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $att)
                        <tr>
                            <td class="text-xs font-semibold text-slate-800">{{ $att->tanggal->format('d M Y') }}</td>
                            <td>
                                <div class="text-xs font-bold text-slate-900">{{ $att->student->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-400">NIS: {{ $att->student->nis }}</div>
                            </td>
                            <td>
                                <span class="text-xs font-semibold text-slate-700">{{ $att->student->kelas }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $att->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : ($att->status === 'sakit' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800') }}">
                                    {{ ucfirst($att->status) }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-600 font-mono">{{ $att->jam_masuk ?? '-' }}</td>
                            <td class="text-xs text-slate-500 italic">{{ $att->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-xs text-slate-400">Belum ada catatan presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
    </div>
    </div>
</x-app-layout>

