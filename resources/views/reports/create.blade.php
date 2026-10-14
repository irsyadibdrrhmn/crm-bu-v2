<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Rekap Nilai Siswa</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Input Nilai Baru</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Input Capaian Nilai Akademik Siswa</h1>
                <p class="text-xs text-slate-500 mt-0.5">Penilaian harian, tugas, PTS, dan semester untuk siswa SMA Binaul Ummah.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn-light text-xs">← Batal & Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="panel p-6 sm:p-8" x-data="{
            tugas: {{ old('nilai_tugas', 0) }},
            uts: {{ old('nilai_uts', 0) }},
            uas: {{ old('nilai_uas', 0) }},
            get akhir() {
                let t = parseFloat(this.tugas) || 0;
                let m = parseFloat(this.uts) || 0;
                let a = parseFloat(this.uas) || 0;
                return Math.round(((t * 0.3) + (m * 0.3) + (a * 0.4)) * 10) / 10;
            },
            get grade() {
                let score = this.akhir;
                if (score >= 88) return 'A (Sangat Baik)';
                if (score >= 78) return 'B (Baik)';
                if (score >= 68) return 'C (Cukup)';
                return 'D (Kurang)';
            }
        }">
            <form method="POST" action="{{ route('reports.store') }}" class="space-y-6">
                @csrf

                <!-- Pilih Siswa -->
                <div>
                    <label class="label-modern">Siswa Penerima Nilai <span class="text-rose-500">*</span></label>
                    <select name="student_id" class="input-modern" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($students as $st)
                            <option value="{{ $st->id }}" {{ old('student_id', $selectedStudentId) == $st->id ? 'selected' : '' }}>
                                {{ $st->nama_lengkap }} (Kelas: {{ $st->kelas }} - NIS: {{ $st->nis }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Mata Pelajaran -->
                    <div class="sm:col-span-2">
                        <label class="label-modern">Mata Pelajaran <span class="text-rose-500">*</span></label>
                        <select name="mata_pelajaran" class="input-modern" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mapelList as $mp)
                                <option value="{{ $mp }}" {{ old('mata_pelajaran') === $mp ? 'selected' : '' }}>{{ $mp }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('mata_pelajaran')" class="mt-1" />
                    </div>

                    <!-- KKM -->
                    <div>
                        <label class="label-modern">KKM Minimum <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.1" name="kkm" value="{{ old('kkm', 75) }}" class="input-modern" required>
                        <x-input-error :messages="$errors->get('kkm')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Semester -->
                    <div>
                        <label class="label-modern">Semester <span class="text-rose-500">*</span></label>
                        <select name="semester" class="input-modern" required>
                            <option value="Ganjil" {{ old('semester') === 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                            <option value="Genap" {{ old('semester') === 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                        </select>
                        <x-input-error :messages="$errors->get('semester')" class="mt-1" />
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="label-modern">Tahun Ajaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', '2025/2026') }}" class="input-modern" required>
                        <x-input-error :messages="$errors->get('tahun_ajaran')" class="mt-1" />
                    </div>
                </div>

                <!-- Komponen Nilai & Kalkulator Otomatis -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Komponen Penilaian (0 - 100)</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="label-modern">Nilai Tugas (Bobot 30%)</label>
                            <input type="number" step="0.1" max="100" min="0" name="nilai_tugas" x-model="tugas" class="input-modern" placeholder="0 - 100">
                        </div>
                        <div>
                            <label class="label-modern">Nilai UTS/PTS (Bobot 30%)</label>
                            <input type="number" step="0.1" max="100" min="0" name="nilai_uts" x-model="uts" class="input-modern" placeholder="0 - 100">
                        </div>
                        <div>
                            <label class="label-modern">Nilai UAS/PAS (Bobot 40%)</label>
                            <input type="number" step="0.1" max="100" min="0" name="nilai_uas" x-model="uas" class="input-modern" placeholder="0 - 100">
                        </div>
                    </div>

                    <!-- Estimasi Nilai Akhir -->
                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="text-slate-600 font-semibold">Simulasi Nilai Akhir:</span>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-indigo-700" x-text="akhir"></span>
                            <span class="badge bg-indigo-100 text-indigo-800" x-text="grade"></span>
                        </div>
                    </div>
                </div>

                <!-- Catatan Guru -->
                <div>
                    <label class="label-modern">Catatan Guru Pengampu / Evaluasi Belajar</label>
                    <textarea name="catatan_guru" rows="3" class="input-modern" placeholder="Contoh: Penguasaan konsep sangat baik, perlu ditingkatkan ketelitian pengerjaan soal cerita.">{{ old('catatan_guru') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan_guru')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('reports.index') }}" class="btn-light text-xs">Batal</a>
                    <button type="submit" class="btn-indigo text-xs">Simpan Nilai Siswa</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

