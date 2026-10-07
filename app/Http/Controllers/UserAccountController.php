<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['studentProfile', 'children'])->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $unlinkedStudents = Student::whereNull('user_id')->orWhereNull('parent_id')->orderBy('nama_lengkap')->get();

        return view('admin.users.index', compact('users', 'unlinkedStudents'));
    }

    public function create()
    {
        $students = Student::orderBy('nama_lengkap')->get();
        return view('admin.users.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,parent,student',
            'password' => 'required|string|min:8',
            'student_id' => 'nullable|exists:students,id',
            // Jika role adalah student dan ingin membuat profil siswa baru secara langsung
            'create_new_student' => 'nullable|boolean',
            'nis' => 'nullable|required_if:create_new_student,1|string|unique:students,nis',
            'kelas' => 'nullable|required_if:create_new_student,1|string',
            'jurusan' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Hubungkan ke data siswa yang dipilih
        if ($request->filled('student_id')) {
            $student = Student::find($request->student_id);
            if ($student) {
                if ($request->role === 'parent') {
                    $student->parent_id = $user->id;
                    $student->save();
                } elseif ($request->role === 'student') {
                    $student->user_id = $user->id;
                    $student->save();
                }
            }
        } elseif ($request->boolean('create_new_student') && $request->role === 'student') {
            // Buat record siswa baru
            Student::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nama_lengkap' => $request->name,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan ?? 'MIPA',
                'status_aktif' => true,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Akun {$user->name} ({$user->role}) berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $students = Student::orderBy('nama_lengkap')->get();
        $user->load(['studentProfile', 'children']);

        return view('admin.users.edit', compact('user', 'students'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,parent,student',
            'password' => 'nullable|string|min:8',
            'student_id' => 'nullable|exists:students,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->filled('student_id')) {
            $student = Student::find($request->student_id);
            if ($student) {
                if ($user->role === 'parent') {
                    $student->parent_id = $user->id;
                    $student->save();
                } elseif ($user->role === 'student') {
                    $student->user_id = $user->id;
                    $student->save();
                }
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Data akun {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Putuskan relasi sebelum menghapus
        Student::where('user_id', $user->id)->update(['user_id' => null]);
        Student::where('parent_id', $user->id)->update(['parent_id' => null]);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus dari sistem.');
    }
}

