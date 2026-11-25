<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPhoto extends Model
{
    protected $fillable = [
        'ticket_id',
        'activity_id',
        'photo_path',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function activity()
    {
        return $this->belongsTo(TicketActivity::class, 'activity_id');
    }
}
