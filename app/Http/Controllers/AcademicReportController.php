<?php

namespace App\Http\Controllers;

use App\Models\AcademicReport;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $query = Student::with('academicReports')->orderBy('kelas')->orderBy('nama_lengkap');

            if ($request->filled('kelas')) {
                $query->where('kelas', $request->kelas);
            }
            if ($request->filled('search')) {
                $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('nis', 'like', '%' . $request->search . '%');
            }

            $students = $query->paginate(15)->withQueryString();
            $kelasList = Student::select('kelas')->distinct()->pluck('kelas');

            return view('reports.index_admin', compact('students', 'kelasList'));
        }

        if ($user->isParent()) {
            $children = $user->children()->with(['academicReports' => function ($q) {
                $q->orderBy('mata_pelajaran');
            }])->get();
            return view('reports.index_parent', compact('children'));
        }

        // Student
        $student = $user->studentProfile()->with(['academicReports' => function ($q) {
            $q->orderBy('mata_pelajaran');
        }])->first();

        return view('reports.index_student', compact('student'));
    }

    public function create(Request $request)
    {
        $students = Student::orderBy('kelas')->orderBy('nama_lengkap')->get();
        $selectedStudentId = $request->query('student_id');

        $mapelList = [
            'Matematika Peminatan',
            'Matematika Wajib',
            'Fisika',
            'Kimia',
            'Biologi',
            'Bahasa Inggris',
            'Bahasa Indonesia',
            'Pendidikan Agama Islam',
            'Sejarah Indonesia',
            'Sosiologi',
            'Geografi',
            'Ekonomi',
            'Informatika',
            'Seni Budaya',
            'PJOK',
        ];

        return view('reports.create', compact('students', 'selectedStudentId', 'mapelList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'mata_pelajaran' => 'required|string|max:100',
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:20',
            'kkm' => 'required|numeric|min:0|max:100',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'catatan_guru' => 'nullable|string|max:500',
        ]);

        $tugas = $request->nilai_tugas ?? 0;
        $uts = $request->nilai_uts ?? 0;
        $uas = $request->nilai_uas ?? 0;

        // Formula nilai akhir bobot 30% tugas, 30% uts, 40% uas
        $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 1);
        $grade = $akhir >= 88 ? 'A' : ($akhir >= 78 ? 'B' : ($akhir >= 68 ? 'C' : 'D'));

        AcademicReport::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'mata_pelajaran' => $request->mata_pelajaran,
                'semester' => $request->semester,
                'tahun_ajaran' => $request->tahun_ajaran,
            ],
            [
                'kkm' => $request->kkm,
                'nilai_tugas' => $tugas,
                'nilai_uts' => $uts,
                'nilai_uas' => $uas,
                'nilai_akhir' => $akhir,
                'grade' => $grade,
                'catatan_guru' => $request->catatan_guru,
            ]
        );

        return redirect()->route('reports.index')->with('success', 'Nilai akademik siswa berhasil disimpan.');
    }

    public function edit(AcademicReport $report)
    {
        $report->load('student');
        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, AcademicReport $report)
    {
        $request->validate([
            'kkm' => 'required|numeric|min:0|max:100',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
            'catatan_guru' => 'nullable|string|max:500',
        ]);

        $tugas = $request->nilai_tugas ?? $report->nilai_tugas;
        $uts = $request->nilai_uts ?? $report->nilai_uts;
        $uas = $request->nilai_uas ?? $report->nilai_uas;

        $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 1);
        $grade = $akhir >= 88 ? 'A' : ($akhir >= 78 ? 'B' : ($akhir >= 68 ? 'C' : 'D'));

        $report->update([
            'kkm' => $request->kkm,
            'nilai_tugas' => $tugas,
            'nilai_uts' => $uts,
            'nilai_uas' => $uas,
            'nilai_akhir' => $akhir,
            'grade' => $grade,
            'catatan_guru' => $request->catatan_guru,
        ]);

        return redirect()->route('students.show', $report->student_id)->with('success', 'Nilai mata pelajaran berhasil diperbarui.');
    }

    public function destroy(AcademicReport $report)
    {
        $studentId = $report->student_id;
        $report->delete();

        return redirect()->route('students.show', $studentId)->with('success', 'Data nilai berhasil dihapus.');
    }
}
