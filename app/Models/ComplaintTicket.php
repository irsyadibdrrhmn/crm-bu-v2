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
        'pelapor_role',
        'student_id',
        'kategori',
        'urgensi',
        'sla_target_hours',
        'status',
        'skor_prioritas',
        'judul',
        'deskripsi',
        'faktor_penyebab',
        'tindakan_solusi',
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
            'sla_target_hours' => 'integer',
            'skor_prioritas' => 'float',
        ];
    }

    /**
     * Menghitung SLA jam tersisa atau keterlambatan penanganan
     */
    public function getSlaRemainingHoursAttribute(): ?float
    {
        if ($this->status === 'selesai' && $this->resolved_at) {
            $diffHours = $this->created_at->diffInHours($this->resolved_at, false);
            return $this->sla_target_hours - $diffHours;
        }

        $elapsed = $this->created_at->diffInHours(now(), false);
        return $this->sla_target_hours - $elapsed;
    }

    /**
     * Status SLA: 'on_track', 'near_breach', 'breached'
     */
    public function getSlaStatusAttribute(): string
    {
        $remaining = $this->sla_remaining_hours;
        if ($this->status === 'selesai') {
            return $remaining >= 0 ? 'met' : 'breached';
        }

        if ($remaining < 0) {
            return 'breached'; // Melebihi batas waktu SLA
        } elseif ($remaining <= 8) {
            return 'warning'; // Mendekati batas waktu SLA (< 8 jam tersisa)
        }
        return 'on_track';
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

