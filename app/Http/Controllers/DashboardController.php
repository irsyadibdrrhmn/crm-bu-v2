<?php

namespace App\Http\Controllers;

use App\Models\AcademicReport;
use App\Models\Attendance;
use App\Models\ComplaintTicket;
use App\Models\Pengumuman;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. DASHBOARD ANALYTICAL & OLAP UNTUK ADMIN & STAF PENDIDIK (GURU & BK)
        if ($user->isStaff()) {
            // Total & Status Tiket
            $totalTickets = ComplaintTicket::count();
            $openTickets = ComplaintTicket::where('status', 'menunggu')->count();
            $inProgressTickets = ComplaintTicket::where('status', 'diproses')->count();
            $resolvedTickets = ComplaintTicket::where('status', 'selesai')->count();
            $highUrgencyTickets = ComplaintTicket::whereIn('urgensi', ['tinggi', 'darurat'])
                ->whereIn('status', ['menunggu', 'diproses'])
                ->count();

            // Customer Satisfaction Score (CSAT 1 - 5)
            $csatAverage = ComplaintTicket::whereNotNull('rating_kepuasan')->avg('rating_kepuasan') ?? 0;
            $csatCount = ComplaintTicket::whereNotNull('rating_kepuasan')->count();

            // Rata-rata response & SLA resolution (dalam Jam)
            $isMysql = DB::getDriverName() === 'mysql';
            $hourDiffExpr = $isMysql
                ? "TIMESTAMPDIFF(HOUR, created_at, resolved_at)"
                : "((julianday(resolved_at) - julianday(created_at)) * 24)";

            $avgResolutionHours = ComplaintTicket::whereNotNull('resolved_at')
                ->selectRaw("AVG({$hourDiffExpr}) as avg_hours")
                ->value('avg_hours') ?? 0;

            // OLAP Breakdown: Tiket berdasarkan Kategori
            $ticketsByCategory = ComplaintTicket::select('kategori', DB::raw('count(*) as total'))
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->get();

            // OLAP Breakdown: Tiket berdasarkan Status
            $ticketsByStatus = ComplaintTicket::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();

            // Siswa berisiko akademik (Nilai di bawah KKM < 75)
            $academicAtRiskCount = Student::whereHas('academicReports', function ($q) {
                $q->where('nilai_akhir', '<', 75);
            })->count();

            // Presensi hari ini / aggregate rate
            $totalAttendances = Attendance::count();
            $presentCount = Attendance::where('status', 'hadir')->count();
            $attendanceRate = $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 100;

            // Tiket aduan mendesak / terbaru yang memerlukan tindak lanjut
            $recentTickets = ComplaintTicket::with(['user', 'student'])
                ->latest()
                ->limit(6)
                ->get();

            // Siswa total
            $totalStudents = Student::where('status_aktif', true)->count();

            return view('dashboard.admin', compact(
                'totalTickets',
                'openTickets',
                'inProgressTickets',
                'resolvedTickets',
                'highUrgencyTickets',
                'csatAverage',
                'csatCount',
                'avgResolutionHours',
                'ticketsByCategory',
                'ticketsByStatus',
                'academicAtRiskCount',
                'attendanceRate',
                'recentTickets',
                'totalStudents'
            ));
        }

        // 2. DASHBOARD PORTAL ORANG TUA / WALI
        if ($user->isParent()) {
            $children = $user->children()->with(['academicReports', 'attendances'])->get();
            $myTickets = ComplaintTicket::where('user_id', $user->id)->latest()->limit(5)->get();
            $announcements = Pengumuman::whereIn('target_audience', ['semua', 'orang_tua'])
                ->latest('tanggal_publish')
                ->limit(4)
                ->get();

            return view('dashboard.parent', compact('children', 'myTickets', 'announcements'));
        }

        // 3. DASHBOARD PORTAL SISWA
        $student = $user->studentProfile()->with(['academicReports', 'attendances'])->first();
        $myTickets = ComplaintTicket::where('user_id', $user->id)->latest()->limit(5)->get();
        $announcements = Pengumuman::whereIn('target_audience', ['semua', 'siswa'])
            ->latest('tanggal_publish')
            ->limit(4)
            ->get();

        return view('dashboard.student', compact('student', 'myTickets', 'announcements'));
    }
}
