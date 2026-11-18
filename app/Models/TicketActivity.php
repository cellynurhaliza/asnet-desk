<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketActivity extends Model
{
    protected $fillable = [
        'ticket_id',
        'activity_type',
        'description',
        'performed_by',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
