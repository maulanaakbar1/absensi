<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'siswa';

    protected $fillable = [
        'name',
        'email',
        'password',
        'nis',
        'kelas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function organisasi()
    {
        return $this->belongsToMany(Organisasi::class, 'organisasi_siswa');
    }
}