<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'semester',
        'tahun_ajaran',
        'mata_pelajaran',
        'kkm',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade',
        'catatan_guru',
    ];

    protected function casts(): array
    {
        return [
            'kkm' => 'decimal:2',
            'nilai_tugas' => 'decimal:2',
            'nilai_uts' => 'decimal:2',
            'nilai_uas' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

