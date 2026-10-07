<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Audit Trail</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Notifikasi Terkirim</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Log Komunikasi & Broadcast Email</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi riwayat pengiriman notifikasi otomatis rapor, presensi, dan status aduan.</p>
        </div>
    </x-slot>

    <div class="panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Waktu Kirim</th>
                        <th>Kanal</th>
                        <th>Penerima</th>
                        <th>Subjek / Notifikasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="text-xs font-semibold text-slate-800">{{ $log->sent_at ? $log->sent_at->format('d/m/Y H:i') : $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-indigo-50 text-indigo-700 uppercase font-bold">{{ $log->channel }}</span>
                            </td>
                            <td>
                                <div class="text-xs font-bold text-slate-900">{{ $log->user ? $log->user->name : 'Sistem' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $log->user ? $log->user->email : '-' }}</div>
                            </td>
                            <td class="max-w-md">
                                <p class="text-xs font-bold text-slate-800">{{ $log->subject }}</p>
                                <p class="text-xs text-slate-500 truncate mt-0.5">{{ $log->content }}</p>
                            </td>
                            <td>
                                <span class="badge {{ $log->status === 'terkirim' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-xs text-slate-400">Belum ada riwayat komunikasi otomatis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>

