<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'nis',
        'nisn',
        'nama_lengkap',
        'kelas',
        'jurusan',
        'jenis_kelamin',
        'tahun_ajaran',
        'no_hp_ortu',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function academicReports()
    {
        return $this->hasMany(AcademicReport::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function complaintTickets()
    {
        return $this->hasMany(ComplaintTicket::class);
    }
}

