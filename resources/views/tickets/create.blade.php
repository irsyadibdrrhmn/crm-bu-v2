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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

                    <!-- Urgensi -->
                    <div>
                        <label class="label-modern">Tingkat Urgensi <span class="text-rose-500">*</span></label>
                        <select name="urgensi" class="input-modern" required>
                            <option value="rendah" {{ old('urgensi') === 'rendah' ? 'selected' : '' }}>Rendah (Aspirasi biasa / info umum)</option>
                            <option value="sedang" {{ old('urgensi', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang (Kendala standar, tanggap 24-48 jam)</option>
                            <option value="tinggi" {{ old('urgensi') === 'tinggi' ? 'selected' : '' }}>Tinggi (Mengganggu KBM, tanggap &lt; 24 jam)</option>
                            <option value="darurat" {{ old('urgensi') === 'darurat' ? 'selected' : '' }}>Darurat (Keamanan / medis / mendesak)</option>
                        </select>
                        <x-input-error :messages="$errors->get('urgensi')" class="mt-1" />
                    </div>
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
                    <textarea name="deskripsi" rows="5" class="input-modern" placeholder="Tuliskan secara lengkap lokasi, waktu kejadian, dan permohonan solusi yang diharapkan..." required>{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
                </div>

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

