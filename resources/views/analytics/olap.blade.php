<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded">Analytical CRM Engine</span>
                    <span class="text-xs text-slate-400">/</span>
                    <span class="text-xs font-medium text-slate-500">Online Analytical Processing (OLAP)</span>
                </div>
                <h1 class="mt-1 text-2xl font-extrabold text-slate-900 tracking-tight">Pemrosesan Analitis Multidimensi & Mutu Layanan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Analisis data terintegrasi multi-sudut pandang (Kategori, Stakeholder Pelapor, Urgensi, SLA, dan CSAT) berbasis konsep E.F. Codd et al. & Rahman et al.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="btn-light text-xs">
                    ← Kembali ke Dashboard
                </a>
                <a href="{{ route('tickets.create') }}" class="btn-indigo text-xs">
                    + Input Tiket Permasalahan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. FASE CRM FRAMEWORK (Rahman et al., 2018) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Fase 1: Acquire -->
            <div class="panel p-5 border-t-4 border-t-sky-500 bg-gradient-to-br from-white to-sky-50/30">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-sky-600 bg-sky-100 px-2 py-0.5 rounded">Fase 1: Acquire</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-2">Akses & Adopsi Portal</h3>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalUsers }}</span>
                    <span class="text-xs text-slate-500 font-semibold">Total Pengguna Aktif</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Wali Murid Terhubung:</span>
                        <span class="font-bold text-slate-900">{{ $activeParents }} Akun</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Siswa Terdaftar:</span>
                        <span class="font-bold text-slate-900">{{ $activeStudents }} Akun</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Guru & Konselor BP/BK:</span>
                        <span class="font-bold text-emerald-700">{{ $activeTeachers }} Akun</span>
                    </div>
                </div>
            </div>

            <!-- Fase 2: Enhance -->
            <div class="panel p-5 border-t-4 border-t-indigo-600 bg-gradient-to-br from-white to-indigo-50/30">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded">Fase 2: Enhance</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-2">Transparansi Akademik & Presensi</h3>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $attendanceRate }}%</span>
                    <span class="text-xs text-slate-500 font-semibold">Tingkat Kehadiran Siswa</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Total Rapor Evaluasi:</span>
                        <span class="font-bold text-slate-900">{{ $totalReports }} Mata Pelajaran</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Rata-Rata Nilai Akhir:</span>
                        <span class="font-bold text-indigo-700">{{ number_format($avgAcademicScore, 1) }} / 100</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Komunikasi Real-Time:</span>
                        <span class="font-bold text-emerald-700">Aktif Terintegrasi</span>
                    </div>
                </div>
            </div>

            <!-- Fase 3: Retain -->
            <div class="panel p-5 border-t-4 border-t-emerald-600 bg-gradient-to-br from-white to-emerald-50/30">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded">Fase 3: Retain</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-2">Kepuasan Layanan & Resolusi SLA</h3>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($avgCsat, 1) }}</span>
                    <span class="text-xs text-amber-600 font-bold">/ 5.0 (CSAT Indeks)</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Resolution Rate:</span>
                        <span class="font-bold text-emerald-700">{{ $resolutionRate }}% ({{ $resolvedCount }} Tiket)</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Rata-Rata Durasi SLA:</span>
                        <span class="font-bold text-slate-900">{{ number_format($avgResolutionHours, 1) }} Jam Selesai</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Standar SLA Maksimal:</span>
                        <span class="font-bold text-slate-500">&lt; 48 Jam</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. OLAP SLICE & DICE FILTER BAR -->
        <div class="panel p-5 border border-indigo-100 bg-slate-50/70">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Operasi OLAP: Slice & Dice (Filter Multidimensi)</h3>
                </div>
                <span class="text-[11px] text-slate-500">Saring kubus data secara dinamis berdasarkan kombinasi dimensi</span>
            </div>

            <form method="GET" action="{{ route('analytics.olap') }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <!-- Dimensi 1: Kategori -->
                <div>
                    <label class="label-modern text-xs">Dimensi Kategori Masalah</label>
                    <select name="kategori" class="input-modern !py-1.5 text-xs">
                        <option value="">Semua Kategori (Cube Roll-Up)</option>
                        <option value="akademik" {{ $selectedKategori === 'akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="fasilitas" {{ $selectedKategori === 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="layanan_konseling" {{ $selectedKategori === 'layanan_konseling' ? 'selected' : '' }}>Layanan Konseling / BK</option>
                        <option value="kedisiplinan" {{ $selectedKategori === 'kedisiplinan' ? 'selected' : '' }}>Kedisiplinan Siswa</option>
                        <option value="keuangan" {{ $selectedKategori === 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                        <option value="administrasi" {{ $selectedKategori === 'administrasi' ? 'selected' : '' }}>Administrasi</option>
                        <option value="ekstrakurikuler" {{ $selectedKategori === 'ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                    </select>
                </div>

                <!-- Dimensi 2: Stakeholder Pelapor -->
                <div>
                    <label class="label-modern text-xs">Dimensi Stakeholder Pelapor</label>
                    <select name="pelapor_role" class="input-modern !py-1.5 text-xs">
                        <option value="">Semua Pelapor</option>
                        <option value="guru" {{ $selectedRole === 'guru' ? 'selected' : '' }}>Guru / Wali Kelas</option>
                        <option value="bk" {{ $selectedRole === 'bk' ? 'selected' : '' }}>Guru BP / BK</option>
                        <option value="parent" {{ $selectedRole === 'parent' ? 'selected' : '' }}>Orang Tua Siswa</option>
                        <option value="student" {{ $selectedRole === 'student' ? 'selected' : '' }}>Siswa Aktif</option>
                    </select>
                </div>

                <!-- Dimensi 3: Urgensi -->
                <div>
                    <label class="label-modern text-xs">Dimensi Urgensi Masalah</label>
                    <select name="urgensi" class="input-modern !py-1.5 text-xs">
                        <option value="">Semua Skala Urgensi</option>
                        <option value="darurat" {{ $selectedUrgensi === 'darurat' ? 'selected' : '' }}>Darurat (SLA 12 Jam)</option>
                        <option value="tinggi" {{ $selectedUrgensi === 'tinggi' ? 'selected' : '' }}>Tinggi (SLA 24 Jam)</option>
                        <option value="sedang" {{ $selectedUrgensi === 'sedang' ? 'selected' : '' }}>Sedang (SLA 48 Jam)</option>
                        <option value="rendah" {{ $selectedUrgensi === 'rendah' ? 'selected' : '' }}>Rendah (SLA 72 Jam)</option>
                    </select>
                </div>

                <!-- Dimensi 4: Status -->
                <div>
                    <label class="label-modern text-xs">Dimensi Status Resolusi</label>
                    <select name="status" class="input-modern !py-1.5 text-xs">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ $selectedStatus === 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="diproses" {{ $selectedStatus === 'diproses' ? 'selected' : '' }}>Dalam Proses Solusi</option>
                        <option value="selesai" {{ $selectedStatus === 'selesai' ? 'selected' : '' }}>Selesai Teratasi</option>
                    </select>
                </div>

                <!-- Tombol Eksekusi Slice -->
                <div class="flex gap-2">
                    <button type="submit" class="btn-indigo text-xs w-full !py-2">
                        Jalankan Slice OLAP
                    </button>
                    <a href="{{ route('analytics.olap') }}" class="btn-light text-xs !py-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- 3. TABEL CROSSTAB 1: Matriks Dimensi Kategori x Dimensi Stakeholder Pelapor -->
        <div class="panel overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-slate-50/50">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">OLAP Crosstab Matrix 1</span>
                        <h3 class="text-base font-bold text-slate-900">Distribusi Kategori Keluhan \(\times\) Stakeholder Pelapor</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Analisis tabulasi silang aduan yang diinput oleh Guru/Wali, Guru BP/BK, Orang Tua, dan Siswa.</p>
                </div>
                <span class="text-xs text-slate-400 font-mono">Aggregated via SQL Cube Group-By</span>
            </div>

            <div class="overflow-x-auto">
                <table class="table-modern text-xs">
                    <thead>
                        <tr>
                            <th>Kategori Permasalahan</th>
                            <th class="text-center bg-emerald-50 text-emerald-800">Guru / Wali Kelas</th>
                            <th class="text-center bg-purple-50 text-purple-800">Guru BP / BK</th>
                            <th class="text-center bg-sky-50 text-sky-800">Orang Tua</th>
                            <th class="text-center bg-amber-50 text-amber-800">Siswa Aktif</th>
                            <th class="text-center bg-slate-100 text-slate-900 font-bold">Total Aduan (Sub-Total)</th>
                            <th>Dominasi Pelapor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($crosstabStakeholder as $row)
                            @php
                                $maxRole = 'Orang Tua';
                                $maxVal = $row->total_parent;
                                if ($row->total_student > $maxVal) { $maxRole = 'Siswa'; $maxVal = $row->total_student; }
                                if ($row->total_guru > $maxVal) { $maxRole = 'Guru / Wali'; $maxVal = $row->total_guru; }
                                if ($row->total_bk > $maxVal) { $maxRole = 'Guru BP/BK'; $maxVal = $row->total_bk; }
                            @endphp
                            <tr>
                                <td class="font-bold text-slate-900 capitalize">
                                    {{ str_replace('_', ' ', $row->kategori) }}
                                </td>
                                <td class="text-center font-semibold text-emerald-700 bg-emerald-50/30">
                                    {{ $row->total_guru }}
                                </td>
                                <td class="text-center font-semibold text-purple-700 bg-purple-50/30">
                                    {{ $row->total_bk }}
                                </td>
                                <td class="text-center font-semibold text-sky-700 bg-sky-50/30">
                                    {{ $row->total_parent }}
                                </td>
                                <td class="text-center font-semibold text-amber-700 bg-amber-50/30">
                                    {{ $row->total_student }}
                                </td>
                                <td class="text-center font-extrabold text-slate-900 bg-slate-50">
                                    {{ $row->total_kategori }} Tiket
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $maxRole }} ({{ $maxVal }})
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-xs text-slate-400">
                                    Tidak ada data aduan pada dimensi yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. TABEL CROSSTAB 2: Kategori x Urgensi & Evaluasi SLA Resolusi + CSAT -->
        <div class="panel overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-slate-50/50">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">OLAP Crosstab Matrix 2</span>
                        <h3 class="text-base font-bold text-slate-900">Tingkat Urgensi, SLA Resolusi, & Indeks Kepuasan (CSAT)</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Analisis efektivitas penyelesaian masalah berdasarkan kecepatan tanggap jam dan ulasan kepuasan.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-modern text-xs">
                    <thead>
                        <tr>
                            <th>Kategori Masalah</th>
                            <th class="text-center text-rose-700">Darurat</th>
                            <th class="text-center text-amber-700">Tinggi</th>
                            <th class="text-center text-sky-700">Sedang</th>
                            <th class="text-center text-slate-700">Rendah</th>
                            <th class="text-center bg-indigo-50 text-indigo-900 font-bold">Rata-Rata Waktu Resolusi</th>
                            <th class="text-center bg-amber-50 text-amber-900 font-bold">Rata-Rata CSAT</th>
                            <th>Status Kinerja SLA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($crosstabSla as $row)
                            <tr>
                                <td class="font-bold text-slate-900 capitalize">
                                    {{ str_replace('_', ' ', $row->kategori) }}
                                </td>
                                <td class="text-center font-bold text-rose-600">
                                    {{ $row->total_darurat }}
                                </td>
                                <td class="text-center font-semibold text-amber-600">
                                    {{ $row->total_tinggi }}
                                </td>
                                <td class="text-center font-semibold text-sky-600">
                                    {{ $row->total_sedang }}
                                </td>
                                <td class="text-center font-semibold text-slate-500">
                                    {{ $row->total_rendah }}
                                </td>
                                <td class="text-center font-bold text-indigo-700 bg-indigo-50/30">
                                    {{ $row->avg_jam_tuntas ? $row->avg_jam_tuntas . ' Jam' : 'Dalam Proses' }}
                                </td>
                                <td class="text-center font-extrabold text-amber-600 bg-amber-50/30">
                                    {{ $row->avg_csat ? '⭐ ' . $row->avg_csat . ' / 5.0' : '-' }}
                                </td>
                                <td>
                                    @if ($row->avg_jam_tuntas && $row->avg_jam_tuntas <= 48)
                                        <span class="badge bg-emerald-100 text-emerald-800">Sesuai SLA Target (&lt; 48 Jam)</span>
                                    @elseif ($row->avg_jam_tuntas)
                                        <span class="badge bg-rose-100 text-rose-800">Perlu Eskalasi SLA</span>
                                    @else
                                        <span class="badge bg-slate-100 text-slate-700">Monitoring Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-xs text-slate-400">
                                    Tidak ada data untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. ANALITIK DETEKSI DINI SISWA BERISIKO (Early Warning At-Risk Student Analytic) -->
        <div class="panel p-6 border-l-4 border-l-rose-500">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-700 bg-rose-50 px-2 py-0.5 rounded">Early Warning Engine</span>
                        <h3 class="text-base font-bold text-slate-900">Analitik Deteksi Dini Siswa Berisiko (Data-Driven Decision)</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Integrasi data multidimensi: Nilai di bawah KKM (&lt; 75), Catatan Kasus Konseling/BK, dan Keterlambatan.</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-rose-50 text-rose-700 rounded-md">Proactive CRM Intervention</span>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="table-modern text-xs">
                    <thead>
                        <tr>
                            <th>Identitas Siswa</th>
                            <th>Kelas & Jurusan</th>
                            <th>Mata Pelajaran &lt; KKM</th>
                            <th>Tiket Kasus / Aduan Aktif</th>
                            <th>Level Risiko</th>
                            <th>Rekomendasi Tindakan Solusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($atRiskStudents as $item)
                            <tr>
                                <td>
                                    <div class="font-bold text-slate-900">{{ $item['student']->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-400">NIS: {{ $item['student']->nis }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-slate-800">{{ $item['student']->kelas }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $item['student']->jurusan }}</div>
                                </td>
                                <td>
                                    <span class="font-bold {{ $item['low_grades_count'] > 0 ? 'text-rose-600' : 'text-slate-500' }}">
                                        {{ $item['low_grades_count'] }} Mapel
                                    </span>
                                </td>
                                <td>
                                    <span class="font-bold {{ $item['tickets_count'] > 0 ? 'text-amber-600' : 'text-slate-500' }}">
                                        {{ $item['tickets_count'] }} Tiket Kasus
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $item['risk_level'] === 'Tinggi' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                        Risiko {{ $item['risk_level'] }}
                                    </span>
                                </td>
                                <td class="font-medium text-slate-800">
                                    <div class="flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                                        <span>{{ $item['rekomendasi'] }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-xs text-slate-400">
                                    Tidak ada siswa yang terindikasi berisiko saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. BUSINESS INTELLIGENCE CHARTS (Ngai et al., 2009) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Distribusi Stakeholder Pelapor -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Dimensi Stakeholder Pelapor (OLAP Slice)</h4>
                        <p class="text-xs text-slate-500">Komposisi aduan: Orang Tua, Siswa, Guru, dan Konselor BK.</p>
                    </div>
                </div>
                <div class="mt-4 h-64">
                    <canvas id="stakeholderChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Komposisi Kategori Masalah -->
            <div class="panel p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Frekuensi Kategori Permasalahan</h4>
                        <p class="text-xs text-slate-500">Pemetaan konsentrasi keluhan untuk perbaikan kebijakan manajemen sekolah.</p>
                    </div>
                </div>
                <div class="mt-4 h-64">
                    <canvas id="olapCategoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Visualisasi Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Stakeholder Distribution
            const stakeholderLabels = {!! json_encode($stakeholderDistribution->pluck('pelapor_role')->map(fn($r) => match($r) {
                'guru' => 'Guru / Wali Kelas',
                'bk' => 'Guru BP / BK',
                'parent' => 'Orang Tua',
                'student' => 'Siswa',
                default => ucfirst($r)
            })) !!};
            const stakeholderData = {!! json_encode($stakeholderDistribution->pluck('total')) !!};

            const ctxStakeholder = document.getElementById('stakeholderChart').getContext('2d');
            new Chart(ctxStakeholder, {
                type: 'pie',
                data: {
                    labels: stakeholderLabels.length ? stakeholderLabels : ['Orang Tua', 'Siswa', 'Guru', 'Guru BP/BK'],
                    datasets: [{
                        data: stakeholderData.length ? stakeholderData : [3, 2, 1, 1],
                        backgroundColor: ['#0284c7', '#f59e0b', '#10b981', '#8b5cf6'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    }
                }
            });

            // Chart 2: Kategori Breakdown
            const categoryLabels = {!! json_encode($categoryBreakdown->pluck('kategori')->map(fn($k) => ucwords(str_replace('_', ' ', $k)))) !!};
            const categoryData = {!! json_encode($categoryBreakdown->pluck('total')) !!};

            const ctxCategory = document.getElementById('olapCategoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'bar',
                data: {
                    labels: categoryLabels.length ? categoryLabels : ['Akademik', 'Konseling', 'Fasilitas', 'Administrasi'],
                    datasets: [{
                        label: 'Total Aduan',
                        data: categoryData.length ? categoryData : [2, 2, 1, 1],
                        backgroundColor: '#6366f1',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                    }
                }
            });
        });
    </script>
</x-app-layout>

