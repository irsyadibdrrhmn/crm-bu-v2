<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $attendances = Attendance::with('student')
                ->latest('tanggal')
                ->paginate(20);
            return view('attendance.index_admin', compact('attendances'));
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
}

