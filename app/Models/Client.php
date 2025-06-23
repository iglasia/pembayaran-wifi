<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'internet_package_id', 
    'name', 
    'ip_address', 
    'phone_number', 
    'house_image', 
    'address', 
    'longitude',
    'latitude', 
    'nik',
    'subscription_status',
    'subscription_ended_at',
    'subscription_reactivated_at',
    'email',
    'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_ended_at' => 'datetime',
        'subscription_reactivated_at' => 'datetime'
    ];

    /**
     * Hash the user's password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function internet_package()
    {
        return $this->belongsTo(InternetPackage::class, 'internet_package_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Transaction::class, 'client_id', 'id');
    }
}
