<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * Kolom yang dapat diisi (mass assignable)
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'description'
    ];

    /**
     * Kolom yang harus disembunyikan
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Relasi ke model User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk pencarian
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
    }

    /**
     * Accessor untuk format nama
     *
     * @return string
     */
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->name));
    }

    /**
     * Mutator untuk nama
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }
}