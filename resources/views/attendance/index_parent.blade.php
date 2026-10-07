<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded">Presensi Ananda</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Monitoring Kehadiran</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Presensi Harian Ananda</h1>
            <p class="text-xs text-slate-500 mt-0.5">Informasi kehadiran, jam tap masuk gerbang sekolah, dan riwayat perizinan sakit/izin.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @forelse ($children as $child)
            <div class="panel p-6">
                <div class="pb-4 mb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">{{ $child->nama_lengkap }}</h2>
                        <p class="text-xs text-slate-500">NIS: {{ $child->nis }} • Kelas: {{ $child->kelas }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-500">Persentase Kehadiran:</span>
                        <span class="text-sm font-bold text-emerald-600 ml-1">
                            {{ $child->attendances->count() ? round(($child->attendances->where('status', 'hadir')->count() / $child->attendances->count()) * 100) : 100 }}%
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
                                <th>Keterangan / Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($child->attendances as $att)
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
            </div>
        @empty
            <div class="panel p-6 text-center text-xs text-slate-400">Belum ada siswa terhubung.</div>
        @endforelse
    </div>
</x-app-layout>

