<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tickets.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Tiket Layanan</a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Form Pengajuan</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Ajukan Tiket Aduan & Layanan Baru</h1>
            <p class="text-xs text-slate-500 mt-0.5">Sampaikan kebutuhan fasilitas, keluhan pembelajaran, permohonan administrasi, atau konseling.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="panel p-6 sm:p-8">
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Siswa Terkait -->
                @if ($students->count() > 0)
                    <div>
                        <label class="label-modern">Siswa Terkait</label>
                        <select name="student_id" class="input-modern">
                            <option value="">-- Umum / Tanpa Siswa Spesifik --</option>
                            @foreach ($students as $st)
                                <option value="{{ $st->id }}" {{ old('student_id', $selectedStudentId) == $st->id ? 'selected' : '' }}>
                                    {{ $st->nama_lengkap }} (Kelas: {{ $st->kelas }} - NIS: {{ $st->nis }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
                    </div>
                @endif

                <!-- Kategori -->
                <div>
                    <label class="label-modern">Kategori Layanan <span class="text-rose-500">*</span></label>
                    <select name="kategori" class="input-modern" required>
                        <option value="akademik" {{ old('kategori') === 'akademik' ? 'selected' : '' }}>Akademik & Pembelajaran</option>
                        <option value="fasilitas" {{ old('kategori') === 'fasilitas' ? 'selected' : '' }}>Fasilitas & Sarana Prasarana</option>
                        <option value="keuangan" {{ old('kategori') === 'keuangan' ? 'selected' : '' }}>Keuangan & Biaya Sekolah</option>
                        <option value="kedisiplinan" {{ old('kategori') === 'kedisiplinan' ? 'selected' : '' }}>Kedisiplinan & Tata Tertib</option>
                        <option value="layanan_konseling" {{ old('kategori') === 'layanan_konseling' ? 'selected' : '' }}>Bimbingan Konseling (BK)</option>
                        <option value="administrasi" {{ old('kategori') === 'administrasi' ? 'selected' : '' }}>Administrasi & Tata Usaha</option>
                        <option value="ekstrakurikuler" {{ old('kategori') === 'ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler & Organisasi</option>
                    </select>
                    <x-input-error :messages="$errors->get('kategori')" class="mt-1" />
                </div>

                <!-- Urgensi dengan Panduan Lengkap -->
                <div x-data="{ selectedUrgensi: '{{ old('urgensi', 'sedang') }}' }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="label-modern !mb-0">Tingkat Urgensi <span class="text-rose-500">*</span></label>
                        <span class="text-[11px] text-slate-500 font-medium">Pilih sesuai skala dampak pada pembelajaran</span>
                    </div>

                    <!-- Radio Grid Pilihan Urgensi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <!-- Rendah -->
                        <label class="relative flex flex-col p-3 rounded-xl border cursor-pointer transition"
                            :class="selectedUrgensi === 'rendah' ? 'border-emerald-500 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Rendah (Aspirasi / Saran)
                                </span>
                                <input type="radio" name="urgensi" value="rendah" x-model="selectedUrgensi" class="text-indigo-600 focus:ring-indigo-500" required>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                                Usulan umum, pertanyaan info santai, atau ide ekskul. <strong>Tidak menghambat</strong> KBM.
                            </p>
                            <span class="text-[10px] text-emerald-700 font-semibold mt-1">Estimasi Tanggap: 3 - 5 Hari Kerja</span>
                        </label>

                        <!-- Sedang -->
                        <label class="relative flex flex-col p-3 rounded-xl border cursor-pointer transition"
                            :class="selectedUrgensi === 'sedang' ? 'border-amber-500 bg-amber-50/40 ring-1 ring-amber-500' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                    Sedang (Kendala Standar)
                                </span>
                                <input type="radio" name="urgensi" value="sedang" x-model="selectedUrgensi" class="text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                                Sarana minor berkendala, jadwal remedial, legalisir rapor. KBM tetap dapat berjalan.
                            </p>
                            <span class="text-[10px] text-amber-700 font-semibold mt-1">Estimasi Tanggap: 24 - 48 Jam</span>
                        </label>

                        <!-- Tinggi -->
                        <label class="relative flex flex-col p-3 rounded-xl border cursor-pointer transition"
                            :class="selectedUrgensi === 'tinggi' ? 'border-rose-500 bg-rose-50/40 ring-1 ring-rose-500' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                    Tinggi (Krusial / Gangguan KBM)
                                </span>
                                <input type="radio" name="urgensi" value="tinggi" x-model="selectedUrgensi" class="text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                                Sarana vital mati (AC Lab Komputer mati total, alat ujian rusak) yang menghentikan KBM 1 kelas.
                            </p>
                            <span class="text-[10px] text-rose-700 font-semibold mt-1">Estimasi Tanggap: &lt; 24 Jam</span>
                        </label>

                        <!-- Darurat -->
                        <label class="relative flex flex-col p-3 rounded-xl border cursor-pointer transition"
                            :class="selectedUrgensi === 'darurat' ? 'border-red-600 bg-red-50/50 ring-2 ring-red-500' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-red-600 animate-ping"></span>
                                    Darurat (Insiden / Keselamatan)
                                </span>
                                <input type="radio" name="urgensi" value="darurat" x-model="selectedUrgensi" class="text-rose-600 focus:ring-rose-500">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                                Kondisi keselamatan jiwa, perundungan berat, korsleting bahaya, atau krisis darurat sekolah.
                            </p>
                            <span class="text-[10px] text-rose-700 font-bold mt-1">Estimasi Tanggap: Segera (Hari yang Sama)</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('urgensi')" class="mt-1" />
                </div>

                <!-- Judul -->
                <div>
                    <label class="label-modern">Judul Tiket Aduan / Permohonan <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: AC Ruang Laboratorium Komputer Tidak Dingin" class="input-modern" required>
                    <x-input-error :messages="$errors->get('judul')" class="mt-1" />
                </div>

                <!-- Deskripsi Rinci -->
                <div>
                    <label class="label-modern">Deskripsi Lengkap & Detail Masalah <span class="text-rose-500">*</span></label>
                    <textarea name="deskripsi" rows="4" class="input-modern" placeholder="Tuliskan secara lengkap lokasi, waktu kejadian, dan permohonan solusi yang diharapkan..." required>{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
                </div>

                @if (auth()->user()->isStaff())
                    <!-- Analisis Awal Pendidik / BP/BK (OLAP Analytical CRM Input) -->
                    <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-200/80 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                            <h4 class="text-xs font-bold text-indigo-950 uppercase tracking-wide">Analisis Awal Pendidik / BP/BK (Analytical CRM)</h4>
                        </div>
                        <p class="text-[11px] text-indigo-800">Sebagai pendidik/konselor, Anda dapat menyertakan diagnosa awal faktor penyebab dan rekomendasi penanganan.</p>
                        
                        <div>
                            <label class="label-modern">Indikasi Faktor Penyebab (Akar Masalah)</label>
                            <input type="text" name="faktor_penyebab" value="{{ old('faktor_penyebab') }}" placeholder="Contoh: Kendala waktu tidur, kesulitan konsep dasar, masalah adaptasi sosial..." class="input-modern">
                        </div>

                        <div>
                            <label class="label-modern">Usulan Rencana Solusi / Tindakan Awal</label>
                            <input type="text" name="tindakan_solusi" value="{{ old('tindakan_solusi') }}" placeholder="Contoh: Konseling individu berkala, remedial terbimbing, koordinasi wali murid..." class="input-modern">
                        </div>
                    </div>
                @endif

                <!-- Lampiran Dokumen / Foto -->
                <div>
                    <label class="label-modern">Lampiran Pendukung (Foto / PDF - Opsional)</label>
                    <input type="file" name="lampiran" class="input-modern file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <p class="text-[11px] text-slate-400 mt-1">Maksimal ukuran file: 4 MB (Format: JPG, PNG, PDF)</p>
                    <x-input-error :messages="$errors->get('lampiran')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('tickets.index') }}" class="btn-light text-xs">Batal</a>
                    <button type="submit" class="btn-indigo text-xs">Kirim Tiket Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

