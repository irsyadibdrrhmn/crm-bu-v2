<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Presensi Siswa</a>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Pencatatan Harian</span>
                </div>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Input Presensi Harian Kelas</h1>
                <p class="text-xs text-slate-500 mt-0.5">Catat kehadiran, keterlambatan, atau izin siswa per rombel kelas.</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn-light text-xs">← Batal & Kembali</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Panel Pemilih Kelas & Tanggal -->
        <div class="panel p-5 bg-white border border-slate-200">
            <form method="GET" action="{{ route('attendance.create') }}" class="flex flex-col sm:flex-row items-end gap-3">
                <div class="w-full sm:w-1/3">
                    <label class="label-modern">Tanggal Presensi</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="input-modern" onchange="this.form.submit()">
                </div>
                <div class="w-full sm:w-1/3">
                    <label class="label-modern">Pilih Rombongan Belajar (Kelas)</label>
                    <select name="kelas" class="input-modern" onchange="this.form.submit()">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ $selectedKelas === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <button type="submit" class="btn-light w-full text-xs py-2.5">Muat Siswa</button>
                </div>
            </form>
        </div>

        <!-- Form Simpan Presensi Masal Per Siswa -->
        @if ($students->count() > 0)
            <div class="panel overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Daftar Siswa Kelas {{ $selectedKelas }}</span>
                        <p class="text-xs text-slate-500">Total: <strong>{{ $students->count() }}</strong> siswa terdaftar aktif</p>
                    </div>
                    <span class="badge bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">

                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Kehadiran</th>
                                    <th>Jam Masuk (Jika Hadir)</th>
                                    <th>Keterangan / Alasan Izin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($students as $st)
                                    @php
                                        $existingAtt = $st->attendances->first();
                                        $currentStatus = $existingAtt ? $existingAtt->status : 'hadir';
                                        $currentJam = $existingAtt ? $existingAtt->jam_masuk : '06:45';
                                        $currentKet = $existingAtt ? $existingAtt->keterangan : '';
                                    @endphp
                                    <tr x-data="{ status: '{{ $currentStatus }}' }">
                                        <td class="font-mono text-xs text-slate-600">{{ $st->nis }}</td>
                                        <td>
                                            <div class="text-xs font-bold text-slate-900">{{ $st->nama_lengkap }}</div>
                                            <div class="text-[11px] text-slate-400">JK: {{ $st->jenis_kelamin }}</div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <label class="flex items-center gap-1 text-xs cursor-pointer font-semibold" :class="status === 'hadir' ? 'text-emerald-700' : 'text-slate-600'">
                                                    <input type="radio" name="presensi[{{ $st->id }}][status]" value="hadir" x-model="status" class="text-emerald-600 focus:ring-emerald-500">
                                                    <span>Hadir</span>
                                                </label>
                                                <label class="flex items-center gap-1 text-xs cursor-pointer font-semibold" :class="status === 'sakit' ? 'text-amber-700' : 'text-slate-600'">
                                                    <input type="radio" name="presensi[{{ $st->id }}][status]" value="sakit" x-model="status" class="text-amber-600 focus:ring-amber-500">
                                                    <span>Sakit</span>
                                                </label>
                                                <label class="flex items-center gap-1 text-xs cursor-pointer font-semibold" :class="status === 'izin' ? 'text-sky-700' : 'text-slate-600'">
                                                    <input type="radio" name="presensi[{{ $st->id }}][status]" value="izin" x-model="status" class="text-sky-600 focus:ring-sky-500">
                                                    <span>Izin</span>
                                                </label>
                                                <label class="flex items-center gap-1 text-xs cursor-pointer font-semibold" :class="status === 'alpha' ? 'text-rose-700' : 'text-slate-600'">
                                                    <input type="radio" name="presensi[{{ $st->id }}][status]" value="alpha" x-model="status" class="text-rose-600 focus:ring-rose-500">
                                                    <span>Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="time" name="presensi[{{ $st->id }}][jam_masuk]" value="{{ $currentJam }}" class="input-modern !py-1 !text-xs font-mono max-w-[130px]" x-bind:disabled="status !== 'hadir'">
                                        </td>
                                        <td>
                                            <input type="text" name="presensi[{{ $st->id }}][keterangan]" value="{{ $currentKet }}" placeholder="Keterangan opsional / no surat dokter" class="input-modern !py-1 !text-xs max-w-xs">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Pastikan status kehadiran telah sesuai dengan daftar absensi fisik/tap gerbang.</span>
                        <button type="submit" class="btn-indigo text-xs">Simpan Rekap Presensi Kelas</button>
                    </div>
                </form>
            </div>
        @else
            <div class="panel p-8 text-center text-xs text-slate-400">
                Tidak ada siswa yang terdaftar aktif di kelas {{ $selectedKelas }}.
            </div>
        @endif
    </div>
</x-app-layout>

