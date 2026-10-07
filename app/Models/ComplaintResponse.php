<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_ticket_id',
        'user_id',
        'pesan',
        'lampiran',
        'is_internal_note',
    ];

    protected function casts(): array
    {
        return [
            'is_internal_note' => 'boolean',
        ];
    }

    public function ticket()
    {
        return $this->belongsTo(ComplaintTicket::class, 'complaint_ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

