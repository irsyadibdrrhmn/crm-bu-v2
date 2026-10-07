<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['nullable', 'in:parent,student'],
            'nis' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $request->input('role', 'parent');
        $nis = $request->input('nis');

        // Cari siswa berdasarkan NIS jika disediakan
        $student = null;
        if ($nis) {
            $student = Student::where('nis', $nis)->first();
            if (!$student) {
                return back()->withInput()->withErrors(['nis' => 'Nomor Induk Siswa (NIS) tidak ditemukan dalam database siswa aktif SMA Binaul Ummah.']);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'password' => Hash::make($request->password),
        ]);

        // Hubungkan otomatis siswa ke akun pendaftar
        if ($student) {
            if ($role === 'parent') {
                $student->parent_id = $user->id;
                $student->save();
            } elseif ($role === 'student') {
                $student->user_id = $user->id;
                $student->save();
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
