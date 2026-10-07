<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'student_id',
        'kategori',
        'urgensi',
        'status',
        'judul',
        'deskripsi',
        'lampiran',
        'rating_kepuasan',
        'ulasan_kepuasan',
        'assigned_to',
        'first_responded_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'first_responded_at' => 'datetime',
            'resolved_at' => 'datetime',
            'rating_kepuasan' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class);
    }
}

