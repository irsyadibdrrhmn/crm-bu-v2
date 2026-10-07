<?php

use App\Http\Controllers\AcademicReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CommunicationLogController;
use App\Http\Controllers\ComplaintTicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root ke dashboard jika login, atau halaman login resmi
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('welcome');

Route::middleware('auth')->group(function () {
    // 1. Unified Dashboard (Admin / Parent / Student)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Complaint Tickets & Service Desk
    Route::get('/tickets', [ComplaintTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [ComplaintTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [ComplaintTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [ComplaintTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/respond', [ComplaintTicketController::class, 'respond'])->name('tickets.respond');
    Route::post('/tickets/{ticket}/rate', [ComplaintTicketController::class, 'rate'])->name('tickets.rate');

    // 3. Academic Reports & Rapor Nilai
    Route::get('/reports', [AcademicReportController::class, 'index'])->name('reports.index');

    // 4. Attendances / Presensi Real-Time
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

    // 5. Khusus Admin / Manajemen Sekolah
    Route::middleware('role:admin')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/communication-logs', [CommunicationLogController::class, 'index'])->name('communication-logs.index');
        Route::resource('pengumuman', PengumumanController::class);

        // Manajemen Akun Pengguna (Siswa & Orang Tua)
        Route::resource('admin/users', \App\Http\Controllers\UserAccountController::class)
            ->names('admin.users');
    });

    // 6. User Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
