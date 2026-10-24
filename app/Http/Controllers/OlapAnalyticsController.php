<?php

namespace App\Http\Controllers;

use App\Models\AcademicReport;
use App\Models\Attendance;
use App\Models\ComplaintTicket;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OlapAnalyticsController extends Controller
{
    /**
     * Engine Pemrosesan Analitis Multidimensi (OLAP) & Analytical CRM
     * Mengacu pada E.F. Codd et al., Srivastava et al., 2008 & Ngai et al., 2009
     */
    public function index(Request $request)
    {
        $selectedKategori = $request->get('kategori');
        $selectedRole = $request->get('pelapor_role');
        $selectedUrgensi = $request->get('urgensi');
        $selectedStatus = $request->get('status');

        // Query Dasar Tiket untuk Slice & Dice OLAP
        $baseQuery = ComplaintTicket::query();

        if ($selectedKategori) {
            $baseQuery->where('kategori', $selectedKategori);
        }
        if ($selectedRole) {
            $baseQuery->where('pelapor_role', $selectedRole);
        }
        if ($selectedUrgensi) {
            $baseQuery->where('urgensi', $selectedUrgensi);
        }
        if ($selectedStatus) {
            $baseQuery->where('status', $selectedStatus);
        }

        // 1. FASE ANALYTICAL CRM METRICS (Rahman et al., 2018)
        // Fase 1: ACQUIRE (Kemudahan akses & partisipasi pengguna portal)
        $totalUsers = User::count();
        $activeParents = User::where('role', 'parent')->count();
        $activeStudents = User::where('role', 'student')->count();
        $activeTeachers = User::whereIn('role', ['guru', 'bk'])->count();

        // Fase 2: ENHANCE (Kualitas hubungan, laporan akademik & presensi real-time)
        $totalReports = AcademicReport::count();
        $avgAcademicScore = AcademicReport::avg('nilai_akhir') ?? 0;
        $totalAttendances = Attendance::count();
        $hadirAttendances = Attendance::where('status', 'hadir')->count();
        $attendanceRate = $totalAttendances > 0 ? round(($hadirAttendances / $totalAttendances) * 100, 1) : 100;

        // Fase 3: RETAIN (Kepercayaan & kepuasan penanganan keluhan terukur)
        $isMysql = DB::getDriverName() === 'mysql';
        $hourDiffExpr = $isMysql
            ? "TIMESTAMPDIFF(HOUR, created_at, resolved_at)"
            : "((julianday(resolved_at) - julianday(created_at)) * 24)";

        $totalTicketsCount = (clone $baseQuery)->count();
        $resolvedCount = (clone $baseQuery)->where('status', 'selesai')->count();
        $resolutionRate = $totalTicketsCount > 0 ? round(($resolvedCount / $totalTicketsCount) * 100, 1) : 0;
        $avgCsat = (clone $baseQuery)->whereNotNull('rating_kepuasan')->avg('rating_kepuasan') ?? 0;
        $avgResolutionHours = (clone $baseQuery)->whereNotNull('resolved_at')
            ->selectRaw("AVG({$hourDiffExpr}) as avg_hours")
            ->value('avg_hours') ?? 0;

        // 2. OPERASI OLAP CROSSTAB 1: Matriks Dimensi Kategori x Dimensi Stakeholder Pelapor
        $crosstabStakeholder = ComplaintTicket::select(
            'kategori',
            DB::raw("SUM(CASE WHEN pelapor_role = 'parent' THEN 1 ELSE 0 END) as total_parent"),
            DB::raw("SUM(CASE WHEN pelapor_role = 'student' THEN 1 ELSE 0 END) as total_student"),
            DB::raw("SUM(CASE WHEN pelapor_role = 'guru' THEN 1 ELSE 0 END) as total_guru"),
            DB::raw("SUM(CASE WHEN pelapor_role = 'bk' THEN 1 ELSE 0 END) as total_bk"),
            DB::raw("COUNT(*) as total_kategori")
        )
            ->groupBy('kategori')
            ->get();

        // 3. OPERASI OLAP CROSSTAB 2: Matriks Dimensi Kategori x Dimensi Urgensi & SLA Kinerja
        $caseHourExpr = $isMysql
            ? "TIMESTAMPDIFF(HOUR, created_at, resolved_at)"
            : "((julianday(resolved_at) - julianday(created_at)) * 24)";

        $crosstabSla = ComplaintTicket::select(
            'kategori',
            DB::raw("SUM(CASE WHEN urgensi = 'darurat' THEN 1 ELSE 0 END) as total_darurat"),
            DB::raw("SUM(CASE WHEN urgensi = 'tinggi' THEN 1 ELSE 0 END) as total_tinggi"),
            DB::raw("SUM(CASE WHEN urgensi = 'sedang' THEN 1 ELSE 0 END) as total_sedang"),
            DB::raw("SUM(CASE WHEN urgensi = 'rendah' THEN 1 ELSE 0 END) as total_rendah"),
            DB::raw("ROUND(AVG(CASE WHEN resolved_at IS NOT NULL THEN {$caseHourExpr} ELSE NULL END), 1) as avg_jam_tuntas"),
            DB::raw("ROUND(AVG(rating_kepuasan), 1) as avg_csat")
        )
            ->groupBy('kategori')
            ->get();

        // 4. ANALITIK DETEKSI DINI SISWA BERISIKO (Early Warning At-Risk Student Analytic)
        // Mengintegrasikan: Nilai Akhir < 75 + Presensi non-hadir + Aduan Kedisiplinan/BK
        $atRiskStudents = Student::with(['user', 'academicReports', 'complaintTickets'])
            ->whereHas('academicReports', function ($q) {
                $q->where('nilai_akhir', '<', 75);
            })
            ->orWhereHas('complaintTickets', function ($q) {
                $q->whereIn('kategori', ['kedisiplinan', 'layanan_konseling', 'akademik'])
                    ->whereIn('urgensi', ['tinggi', 'darurat']);
            })
            ->limit(8)
            ->get()
            ->map(function ($st) {
                $lowGrades = $st->academicReports->where('nilai_akhir', '<', 75)->count();
                $ticketCount = $st->complaintTickets->count();
                $riskLevel = ($lowGrades >= 2 || $ticketCount >= 2) ? 'Tinggi' : 'Sedang';
                return [
                    'student' => $st,
                    'low_grades_count' => $lowGrades,
                    'tickets_count' => $ticketCount,
                    'risk_level' => $riskLevel,
                    'rekomendasi' => $riskLevel === 'Tinggi'
                        ? 'Jadwalkan Konseling Khusus BP/BK & Koordinasi Orang Tua'
                        : 'Remedial Terbimbing & Monitoring Wali Kelas',
                ];
            });

        // 5. CHART DATA UNTUK VISUALISASI BUSINESS INTELLIGENCE
        // Tren Distribusi Stakeholder Pelapor
        $stakeholderDistribution = ComplaintTicket::select('pelapor_role', DB::raw('count(*) as total'))
            ->groupBy('pelapor_role')
            ->get();

        // Data Kategori untuk Bar Chart
        $categoryBreakdown = (clone $baseQuery)->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        return view('analytics.olap', compact(
            'selectedKategori',
            'selectedRole',
            'selectedUrgensi',
            'selectedStatus',
            'totalUsers',
            'activeParents',
            'activeStudents',
            'activeTeachers',
            'totalReports',
            'avgAcademicScore',
            'attendanceRate',
            'totalTicketsCount',
            'resolvedCount',
            'resolutionRate',
            'avgCsat',
            'avgResolutionHours',
            'crosstabStakeholder',
            'crosstabSla',
            'atRiskStudents',
            'stakeholderDistribution',
            'categoryBreakdown'
        ));
    }
}
