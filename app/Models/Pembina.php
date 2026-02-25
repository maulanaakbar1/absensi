<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pembina extends Authenticatable
{
    use Notifiable;

    protected $table = 'pembina';

    protected $fillable = [
        'name',
        'email',
        'password',
        'organisasi_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }
}