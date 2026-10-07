<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['parent', 'user', 'academicReports', 'attendances'])->orderBy('kelas')->orderBy('nama_lengkap');

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate(15)->withQueryString();
        $kelasList = Student::select('kelas')->distinct()->pluck('kelas');

        return view('students.index', compact('students', 'kelasList'));
    }

    public function show(Student $student)
    {
        $student->load(['academicReports', 'attendances' => fn($q) => $q->latest('tanggal')->limit(30), 'complaintTickets.responses']);

        return view('students.show', compact('student'));
    }
}

