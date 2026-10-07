<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Kedisiplinan & Kesiswaan</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Log Presensi</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Monitoring Presensi Seluruh Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">Audit log kedatangan harian gerbang sekolah SMA Binaul Ummah.</p>
        </div>
    </x-slot>

    <div class="panel overflow-hidden">
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
</x-app-layout>

