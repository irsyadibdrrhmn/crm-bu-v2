<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Manajemen & Mutu Sekolah</span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">OLAP Analytical Engine</span>
            </div>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Executive CRM & Service Analytics</h1>
            <p class="text-xs text-slate-500 mt-0.5">Monitoring SLA penanganan keluhan, indeks kepuasan orang tua, dan indikator risiko akademik siswa SMA Binaul Ummah.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- KPI METRICS SUMMARY GRID -->
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
            <!-- Total Aduan & Status -->
            <div class="panel p-5 border-l-4 border-l-indigo-600">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Tiket Layanan</span>
                    <span class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalTickets) }}</span>
                    <span class="text-xs text-amber-600 font-semibold">{{ $openTickets }} Menunggu</span>
                </div>
                <div class="mt-2 flex items-center text-xs text-slate-500 gap-2">
                    <span class="text-sky-600 font-medium">{{ $inProgressTickets }} diproses</span>
                    <span>•</span>
                    <span class="text-emerald-600 font-medium">{{ $resolvedTickets }} selesai</span>
                </div>
            </div>

            <!-- Rasio Penyelesaian Tiket (Resolution Rate) -->
            <div class="panel p-5 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tingkat Resolusi Tiket</span>
                    <span class="rounded-lg bg-amber-50 p-2 text-amber-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                @php
                    $resolutionRate = $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100, 1) : 0;
                @endphp
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $resolutionRate }}%</span>
                    <span class="text-xs text-emerald-600 font-semibold">{{ $resolvedTickets }} Selesai</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Dari total {{ $totalTickets }} tiket layanan masuk</p>
            </div>

            <!-- Rata-rata Waktu Penyelesaian (SLA) -->
            <div class="panel p-5 border-l-4 border-l-emerald-600">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-Rata SLA Selesai</span>
                    <span class="rounded-lg bg-emerald-50 p-2 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ round($avgResolutionHours, 1) }}</span>
                    <span class="text-xs text-slate-500 font-semibold">Jam</span>
                </div>
                <p class="mt-2 text-xs text-emerald-600 font-medium">Standard SLA Target &lt; 48 Jam</p>
            </div>

            <!-- Presensi & Risiko Siswa -->
            <div class="panel p-5 border-l-4 border-l-rose-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tingkat Kehadiran</span>
                    <span class="rounded-lg bg-rose-50 p-2 text-rose-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $attendanceRate }}%</span>
                    <span class="text-xs text-rose-600 font-semibold">{{ $academicAtRiskCount }} Siswa Evaluasi</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Dari {{ $totalStudents }} siswa aktif terdaftar</p>
            </div>
        </div>

        <!-- ANALYTICAL OLAP CHARTS GRID -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Chart 1: Distribusi Kategori Keluhan & Layanan -->
            <div class="panel p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Distribusi Kategori Keluhan (OLAP Breakdown)</h2>
                        <p class="text-xs text-slate-500">Identifikasi tren permasalahan terbanyak di lingkungan sekolah.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md">Real-Time Data</span>
                </div>
                <div class="mt-4 h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Komposisi Status Tiket & SLA -->
            <div class="panel p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Status Resolusi Tiket Layanan</h2>
                        <p class="text-xs text-slate-500">Komposisi kecepatan tindak lanjut aduan siswa dan wali murid.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md">KPI Operasional</span>
                </div>
                <div class="mt-4 h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- RECENT TICKETS & URGENT ACTION TABLE -->
        <div class="panel overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-5 border-b border-slate-200/80 bg-slate-50/50">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Antrean Tiket Layanan & Aduan Terbaru</h2>
                    <p class="text-xs text-slate-500">Daftar keluhan yang membutuhkan penanganan segera dari tim manajemen/staf.</p>
                </div>
                <a href="{{ route('tickets.index') }}" class="btn-indigo text-xs py-2 px-3">
                    Lihat Semua Tiket
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>No. Tiket</th>
                            <th>Pelapor & Siswa</th>
                            <th>Kategori & Judul</th>
                            <th>Urgensi</th>
                            <th>Status</th>
                            <th>Waktu Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentTickets as $ticket)
                            <tr>
                                <td class="font-mono text-xs font-bold text-indigo-600">
                                    {{ $ticket->ticket_number }}
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900 text-xs">{{ $ticket->user->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $ticket->student ? $ticket->student->nama_lengkap . ' (' . $ticket->student->kelas . ')' : 'Umum' }}</div>
                                </td>
                                <td class="max-w-xs">
                                    <div class="text-xs font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $ticket->kategori) }}</div>
                                    <div class="text-xs text-slate-600 truncate">{{ $ticket->judul }}</div>
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
                                    {{ $ticket->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn-light !py-1 !px-2.5 !text-xs font-semibold hover:border-indigo-300 hover:text-indigo-600">
                                        Proses
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-xs text-slate-500">
                                    Tidak ada tiket aduan aktif saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SCRIPT CHART.JS INITIALIZATION -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data Kategori dari Controller
            const categoryLabels = {!! json_encode($ticketsByCategory->pluck('kategori')->map(fn($k) => ucwords(str_replace('_', ' ', $k)))) !!};
            const categoryData = {!! json_encode($ticketsByCategory->pluck('total')) !!};

            // 1. Bar Chart: Kategori Keluhan
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'bar',
                data: {
                    labels: categoryLabels.length ? categoryLabels : ['Akademik', 'Fasilitas', 'Administrasi', 'Ekskul', 'Konseling'],
                    datasets: [{
                        label: 'Jumlah Aduan',
                        data: categoryData.length ? categoryData : [3, 2, 1, 1, 1],
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0 }
                        }
                    }
                }
            });

            // Data Status dari Controller
            const statusLabels = {!! json_encode($ticketsByStatus->pluck('status')->map(fn($s) => ucfirst($s))) !!};
            const statusData = {!! json_encode($ticketsByStatus->pluck('total')) !!};

            // 2. Doughnut Chart: Status Resolusi Tiket
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.length ? statusLabels : ['Menunggu', 'Diproses', 'Selesai'],
                    datasets: [{
                        data: statusData.length ? statusData : [1, 2, 2],
                        backgroundColor: ['#f59e0b', '#0284c7', '#10b981', '#f43f5e'],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
