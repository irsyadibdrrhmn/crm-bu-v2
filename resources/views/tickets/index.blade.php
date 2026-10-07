<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Service Desk</span>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Tiket Aduan & Layanan</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Manajemen Tiket Layanan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pengelolaan keluhan, aspirasi sarana prasarana, konsultasi BK, dan layanan administrasi sekolah.</p>
            </div>
            <div>
                <a href="{{ route('tickets.create') }}" class="btn-indigo text-xs">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajukan Tiket Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (auth()->user()->isAdmin())
            <!-- Filter Bar untuk Admin -->
            <div class="panel p-4">
                <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="label-modern">Status Tiket</label>
                        <select name="status" class="input-modern">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-modern">Kategori</label>
                        <select name="kategori" class="input-modern">
                            <option value="">Semua Kategori</option>
                            <option value="akademik" {{ request('kategori') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="fasilitas" {{ request('kategori') === 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="keuangan" {{ request('kategori') === 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                            <option value="layanan_konseling" {{ request('kategori') === 'layanan_konseling' ? 'selected' : '' }}>Layanan Konseling</option>
                            <option value="administrasi" {{ request('kategori') === 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="ekstrakurikuler" {{ request('kategori') === 'ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                        </select>
                    </div>
                    <div>
                        <label class="label-modern">Tingkat Urgensi</label>
                        <select name="urgensi" class="input-modern">
                            <option value="">Semua Urgensi</option>
                            <option value="rendah" {{ request('urgensi') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ request('urgensi') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ request('urgensi') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="darurat" {{ request('urgensi') === 'darurat' ? 'selected' : '' }}>Darurat</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary w-full text-xs">Filter</button>
                        <a href="{{ route('tickets.index') }}" class="btn-light text-xs">Reset</a>
                    </div>
                </form>
            </div>
        @endif

        <div class="panel overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>No. Tiket</th>
                            <th>Pelapor</th>
                            <th>Siswa Terkait</th>
                            <th>Kategori & Judul</th>
                            <th>Urgensi</th>
                            <th>Status</th>
                            <th>Tanggal Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="font-mono text-xs font-bold text-indigo-600">
                                    {{ $ticket->ticket_number }}
                                </td>
                                <td>
                                    <div class="text-xs font-medium text-slate-900">{{ $ticket->user->name }}</div>
                                    <div class="text-[11px] text-slate-400 capitalize">{{ $ticket->user->role }}</div>
                                </td>
                                <td>
                                    <div class="text-xs text-slate-700">
                                        {{ $ticket->student ? $ticket->student->nama_lengkap : 'Umum' }}
                                    </div>
                                    @if ($ticket->student)
                                        <div class="text-[11px] text-slate-400">{{ $ticket->student->kelas }}</div>
                                    @endif
                                </td>
                                <td class="max-w-xs">
                                    <span class="inline-block text-[11px] font-semibold text-slate-500 uppercase">
                                        {{ str_replace('_', ' ', $ticket->kategori) }}
                                    </span>
                                    <p class="text-xs font-semibold text-slate-900 truncate">{{ $ticket->judul }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-urgensi-{{ $ticket->urgensi }}">
                                        {{ strtoupper($ticket->urgensi) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ticket->status }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn-light !py-1 !px-2.5 !text-xs font-semibold hover:text-indigo-600">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-xs text-slate-400">
                                    Tidak ada tiket yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

