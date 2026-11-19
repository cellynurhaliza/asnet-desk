<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customer';

    protected $fillable = [
        'username',
        'pic',
        'email',
        'phone_number',
        'password',
        'address',
        'service_type',
        'account_type',
        'status',
        'coordinates',
        'photo',
        'ip_address',
        'vlan',
        'pop_location',
        'port_pon',
        'cid',
        'user_location',
        'bandwidth',
        'access_method',
        'connection_type',
        'media',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::created(function (Customer $customer) {
            $customer->forceFill([
                'cid' => 'CID-' . str_pad($customer->id, 5, '0', STR_PAD_LEFT),
            ])->saveQuietly();
        });
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }
}
