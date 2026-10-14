<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = Attendance::with('student')->latest('tanggal');

            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('kelas')) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('kelas', $request->kelas);
                });
            }

            $attendances = $query->paginate(20)->withQueryString();
            $kelasList = Student::select('kelas')->distinct()->pluck('kelas');

            return view('attendance.index_admin', compact('attendances', 'kelasList'));
        }

        if ($user->isParent()) {
            $children = $user->children()->with(['attendances' => function ($q) {
                $q->latest('tanggal')->limit(30);
            }])->get();
            return view('attendance.index_parent', compact('children'));
        }

        // Student
        $student = $user->studentProfile()->with(['attendances' => function ($q) {
            $q->latest('tanggal')->limit(30);
        }])->first();

        return view('attendance.index_student', compact('student'));
    }

    public function create(Request $request)
    {
        $kelasList = Student::select('kelas')->distinct()->pluck('kelas');
        $selectedKelas = $request->query('kelas', $kelasList->first());
        $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

        // Ambil siswa di kelas tersebut beserta data absensi yang sudah ada di tanggal tersebut
        $students = Student::where('kelas', $selectedKelas)
            ->where('status_aktif', true)
            ->with(['attendances' => function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal);
            }])
            ->orderBy('nama_lengkap')
            ->get();

        return view('attendance.create', compact('kelasList', 'selectedKelas', 'tanggal', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas' => 'required|string',
            'presensi' => 'required|array',
            'presensi.*.status' => 'required|in:hadir,sakit,izin,alpha',
            'presensi.*.jam_masuk' => 'nullable|string',
            'presensi.*.keterangan' => 'nullable|string|max:255',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->presensi as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $data['status'],
                    'jam_masuk' => $data['status'] === 'hadir' ? ($data['jam_masuk'] ?? '06:50') : null,
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->route('attendance.index', ['tanggal' => $tanggal, 'kelas' => $request->kelas])
            ->with('success', "Presensi kelas {$request->kelas} tanggal " . Carbon::parse($tanggal)->format('d/m/Y') . " berhasil disimpan.");
    }
}
