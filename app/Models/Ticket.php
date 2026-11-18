<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'customer_id',
        'kategori_id',
        'technician_id',
        'name',
        'complaint',
        'action',
        'reporting_time',
        'ticket_status',
        'priority',
    ];

    protected $casts = [
        'reporting_time' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }

    public function technicians()
    {
        return $this->belongsToMany(
            Technician::class,
            'ticket_technician',
            'ticket_id',
            'technician_id'
        )->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class)
            ->latest();
    }
}
