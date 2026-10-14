<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reports.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Rekap Nilai</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">{{ $report->student->nama_lengkap }}</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Edit Nilai: {{ $report->mata_pelajaran }}</h1>
            </div>
            <a href="{{ route('students.show', $report->student_id) }}" class="btn-light text-xs">← Kembali ke Siswa</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="panel p-6 sm:p-8" x-data="{
            tugas: {{ old('nilai_tugas', $report->nilai_tugas ?? 0) }},
            uts: {{ old('nilai_uts', $report->nilai_uts ?? 0) }},
            uas: {{ old('nilai_uas', $report->nilai_uas ?? 0) }},
            get akhir() {
                let t = parseFloat(this.tugas) || 0;
                let m = parseFloat(this.uts) || 0;
                let a = parseFloat(this.uas) || 0;
                return Math.round(((t * 0.3) + (m * 0.3) + (a * 0.4)) * 10) / 10;
            },
            get grade() {
                let score = this.akhir;
                if (score >= 88) return 'A';
                if (score >= 78) return 'B';
                if (score >= 68) return 'C';
                return 'D';
            }
        }">
            <form method="POST" action="{{ route('reports.update', $report) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700">
                    <p>Siswa: <strong class="text-slate-900">{{ $report->student->nama_lengkap }}</strong> ({{ $report->student->kelas }})</p>
                    <p class="mt-1">Semester: <strong>{{ $report->semester }}</strong> • Tahun Ajaran: <strong>{{ $report->tahun_ajaran }}</strong></p>
                </div>

                <div>
                    <label class="label-modern">KKM Minimum</label>
                    <input type="number" step="0.1" name="kkm" value="{{ old('kkm', $report->kkm) }}" class="input-modern" required>
                    <x-input-error :messages="$errors->get('kkm')" class="mt-1" />
                </div>

                <!-- Komponen Nilai -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Komponen Nilai</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="label-modern">Nilai Tugas (30%)</label>
                            <input type="number" step="0.1" max="100" min="0" name="nilai_tugas" x-model="tugas" class="input-modern">
                        </div>
                        <div>
                            <label class="label-modern">Nilai UTS/PTS (30%)</label>
                            <input type="number" step="0.1" max="100" min="0" name="nilai_uts" x-model="uts" class="input-modern">
                        </div>
                        <div>
                            <label class="label-modern">Nilai UAS/PAS (40%)</label>
                            <input type="number" step="0.1" max="100" min="0" name="nilai_uas" x-model="uas" class="input-modern">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="text-slate-600 font-semibold">Kalkulasi Nilai Akhir:</span>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-extrabold text-indigo-700" x-text="akhir"></span>
                            <span class="badge bg-indigo-100 text-indigo-800" x-text="grade"></span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label-modern">Catatan Guru Pengampu</label>
                    <textarea name="catatan_guru" rows="3" class="input-modern">{{ old('catatan_guru', $report->catatan_guru) }}</textarea>
                    <x-input-error :messages="$errors->get('catatan_guru')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('students.show', $report->student_id) }}" class="btn-light text-xs">Batal</a>
                    <button type="submit" class="btn-indigo text-xs">Perbarui Nilai</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

