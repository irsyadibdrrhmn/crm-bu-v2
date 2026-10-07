<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumumans';

    protected $fillable = [
        'admin_id',
        'judul',
        'konten',
        'target_audience', // semua, siswa, orang_tua
        'tanggal_publish',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publish' => 'date',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
