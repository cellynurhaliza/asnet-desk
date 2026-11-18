<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $table = 'technician';

    protected $fillable = [
        'technician_name',
        'phone_number',
        'active_technician',
        'telegram_username',
        'role_telegram',
    ];

    protected function casts(): array
    {
        return [
            'active_technician' => 'boolean',
        ];
    }

    public function tickets()
    {
        return $this->belongsToMany(
            Ticket::class,
            'ticket_technician',
            'technician_id',
            'ticket_id'
        )->withTimestamps();
    }
}
