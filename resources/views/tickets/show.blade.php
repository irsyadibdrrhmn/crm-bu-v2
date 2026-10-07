<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tickets.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Tiket Layanan</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-mono font-semibold text-slate-700">{{ $ticket->ticket_number }}</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">{{ $ticket->judul }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500">
                    <span>Pelapor: <strong class="text-slate-700">{{ $ticket->user->name }}</strong> ({{ ucfirst($ticket->user->role) }})</span>
                    <span>•</span>
                    <span>Dibuat: {{ $ticket->created_at->format('d M Y H:i') }}</span>
                    <span>•</span>
                    <span class="badge badge-urgensi-{{ $ticket->urgensi }}">{{ strtoupper($ticket->urgensi) }}</span>
                    <span class="badge badge-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</span>
                </div>
            </div>
            <div>
                <a href="{{ route('tickets.index') }}" class="btn-light text-xs">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- DETAIL TIKET & THREAD PERCAKAPAN (2 KOLOM) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Isi Aduan / Deskripsi Awal -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Uraian Aduan Awal</span>
                    <span class="text-xs text-slate-400">{{ $ticket->created_at->diffForHumans() }}</span>
                </div>
                <div class="mt-4 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                    {{ $ticket->deskripsi }}
                </div>
                @if ($ticket->lampiran)
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <span class="text-xs font-semibold text-slate-500">Lampiran Bukti:</span>
                        <a href="{{ asset('storage/' . $ticket->lampiran) }}" target="_blank" class="mt-1 inline-flex items-center gap-1.5 text-xs text-indigo-600 font-semibold hover:underline">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            Lihat File Lampiran
                        </a>
                    </div>
                @endif
            </div>

            <!-- Thread Komunikasi / Balasan -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Histori Penanganan & Tanggapan ({{ $ticket->responses->count() }})</h3>
                
                @forelse ($ticket->responses as $resp)
                    <div class="panel p-5 {{ $resp->user->isAdmin() ? 'border-l-4 border-l-indigo-600 bg-indigo-50/20' : 'bg-white' }}">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-900">{{ $resp->user->name }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded font-semibold {{ $resp->user->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $resp->user->isAdmin() ? 'Petugas / Sekolah' : 'Pemohon' }}
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400">{{ $resp->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <p class="mt-3 text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $resp->pesan }}</p>
                    </div>
                @empty
                    <div class="panel p-6 text-center text-xs text-slate-400">
                        Belum ada respon balasan. Petugas sekolah akan segera menindaklanjuti.
                    </div>
                @endforelse
            </div>

            <!-- Form Balasan Respon -->
            @if ($ticket->status !== 'selesai' || auth()->user()->isAdmin())
                <div class="panel p-6 border-t-2 border-t-indigo-600">
                    <h4 class="text-sm font-bold text-slate-900 mb-4">Tulis Respon / Tindak Lanjut</h4>
                    <form method="POST" action="{{ route('tickets.respond', $ticket) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @if (auth()->user()->isAdmin())
                            <div>
                                <label class="label-modern">Update Status Tiket</label>
                                <select name="status" class="input-modern">
                                    <option value="diproses" {{ $ticket->status === 'diproses' ? 'selected' : '' }}>Diproses (Sedang ditangani tim terkait)</option>
                                    <option value="selesai" {{ $ticket->status === 'selesai' ? 'selected' : '' }}>Selesai (Solusi telah tuntas)</option>
                                    <option value="ditolak" {{ $ticket->status === 'ditolak' ? 'selected' : '' }}>Ditolak / Ditutup</option>
                                </select>
                            </div>
                        @endif

                        <div>
                            <label class="label-modern">Isi Tanggapan</label>
                            <textarea name="pesan" rows="4" class="input-modern" placeholder="Tuliskan keterangan tindakan, progres perbaikan, atau klarifikasi..." required></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-indigo text-xs">Kirim Respon</button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Form Rating Kepuasan (CSAT) untuk Pelapor -->
            @if ($ticket->status === 'selesai' && $ticket->user_id === auth()->id() && !$ticket->rating_kepuasan)
                <div class="panel p-6 border-2 border-amber-300 bg-amber-50/30">
                    <h4 class="text-sm font-bold text-amber-900">Evaluasi Kepuasan Layanan (CSAT)</h4>
                    <p class="text-xs text-amber-700 mt-1">Bantu kami meningkatkan mutu pelayanan SMA Binaul Ummah dengan memberikan rating atas penanganan tiket ini.</p>
                    <form method="POST" action="{{ route('tickets.rate', $ticket) }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="label-modern">Skala Kepuasan (1 - 5 Bintang)</label>
                            <select name="rating_kepuasan" class="input-modern">
                                <option value="5">⭐⭐⭐⭐⭐ 5 - Sangat Memuaskan & Cepat</option>
                                <option value="4">⭐⭐⭐⭐ 4 - Memuaskan</option>
                                <option value="3">⭐⭐⭐ 3 - Cukup Baik</option>
                                <option value="2">⭐⭐ 2 - Kurang Memuaskan</option>
                                <option value="1">⭐ 1 - Sangat Lambat / Tidak Puas</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-modern">Ulasan / Masukan (Opsional)</label>
                            <textarea name="ulasan_kepuasan" rows="2" class="input-modern" placeholder="Tuliskan saran untuk perbaikan layanan ke depan..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary text-xs !bg-amber-600 hover:!bg-amber-700">Kirim Penilaian Mutu</button>
                    </form>
                </div>
            @elseif ($ticket->rating_kepuasan)
                <div class="panel p-5 bg-emerald-50/50 border border-emerald-200">
                    <span class="text-xs font-bold text-emerald-800 uppercase">Indeks Kepuasan Pelapor (CSAT)</span>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-xl font-bold text-slate-900">{{ $ticket->rating_kepuasan }} / 5 Bintang</span>
                    </div>
                    @if ($ticket->ulasan_kepuasan)
                        <p class="mt-2 text-xs text-slate-600 italic">"{{ $ticket->ulasan_kepuasan }}"</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- SIDEBAR INFORMASI SLA & SISWA (1 KOLOM) -->
        <div class="space-y-6">
            <!-- Panel SLA & Informasi Internal -->
            <div class="panel p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 pb-3 border-b border-slate-100">Informasi SLA & Status</h4>
                <dl class="divide-y divide-slate-100 text-xs mt-2">
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Nomor Registrasi</dt>
                        <dd class="font-mono font-bold text-slate-900">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Kategori</dt>
                        <dd class="font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $ticket->kategori) }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Target Tanggap (SLA)</dt>
                        <dd class="font-semibold text-slate-900">&lt; 48 Jam</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Respon Pertama</dt>
                        <dd class="font-semibold text-slate-900">{{ $ticket->first_responded_at ? $ticket->first_responded_at->format('d/m H:i') : 'Menunggu' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Waktu Tuntas</dt>
                        <dd class="font-semibold text-slate-900">{{ $ticket->resolved_at ? $ticket->resolved_at->format('d/m H:i') : '-' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Petugas Penanggung Jawab</dt>
                        <dd class="font-semibold text-slate-900">{{ $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Staff Layanan' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Panel Profil Siswa Terkait -->
            @if ($ticket->student)
                <div class="panel p-5">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 pb-3 border-b border-slate-100">Profil Siswa Terkait</h4>
                    <div class="mt-3">
                        <p class="font-bold text-slate-900 text-sm">{{ $ticket->student->nama_lengkap }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">NIS: {{ $ticket->student->nis }} • Kelas: {{ $ticket->student->kelas }}</p>
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                Lihat Rekap Nilai Akademik →
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

