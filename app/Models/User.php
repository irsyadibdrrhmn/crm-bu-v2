<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role', // admin, parent, student
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Relasi profil siswa jika user adalah siswa
    public function studentProfile()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // Relasi anak-anak jika user adalah parent
    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    // Tiket pengaduan yang diajukan oleh user
    public function complaintTickets()
    {
        return $this->hasMany(ComplaintTicket::class);
    }

    // Respon tiket
    public function complaintResponses()
    {
        return $this->hasMany(ComplaintResponse::class);
    }

    // Pengumuman yang dibuat (admin)
    public function announcements()
    {
        return $this->hasMany(Pengumuman::class, 'admin_id');
    }
}
