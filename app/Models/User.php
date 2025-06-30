<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position_id',
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

      public function isOwner()
    {
        return $this->position_id === 1; // Owner of CaturNET
    }

    public function isAdmin()
    {
        return $this->position_id === 2; // Admin CaturNET
    }

    public function isClient()
    {
        return $this->position_id === null || $this->position_id >= 3; // Asumsi client memiliki position_id >= 3 atau null
    }
}
