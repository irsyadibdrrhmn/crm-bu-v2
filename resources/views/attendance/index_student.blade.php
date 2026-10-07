<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Presensi Siswa</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Rekap Harian</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Presensi Kehadiran Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">Monitoring absensi real-time SMA Binaul Ummah.</p>
        </div>
    </x-slot>

    <div class="panel p-6">
        @if ($student)
            <div class="pb-4 mb-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">{{ $student->nama_lengkap }}</h2>
                    <p class="text-xs text-slate-500">NIS: {{ $student->nis }} • Kelas: {{ $student->kelas }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-500">Tingkat Kehadiran:</span>
                    <span class="text-sm font-bold text-emerald-600 ml-1">
                        {{ $student->attendances->count() ? round(($student->attendances->where('status', 'hadir')->count() / $student->attendances->count()) * 100) : 100 }}%
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status Kehadiran</th>
                            <th>Waktu Tap Masuk</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($student->attendances as $att)
                            <tr>
                                <td class="text-xs font-semibold text-slate-800">{{ $att->tanggal->format('d M Y') }}</td>
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
                                <td colspan="4" class="text-center py-6 text-xs text-slate-400">Belum ada catatan presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 py-6 text-center">Data profil siswa tidak ditemukan.</p>
        @endif
    </div>
</x-app-layout>

