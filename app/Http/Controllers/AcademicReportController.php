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
            $students = Student::with('academicReports')->orderBy('kelas')->orderBy('nama_lengkap')->paginate(15);
            return view('reports.index_admin', compact('students'));
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
}

