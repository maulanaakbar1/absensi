<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $table = 'organisasi'; 
    protected $fillable = [
        'nama_organisasi',
        'keterangan',
    ];


    public function pembina()
    {
        return $this->hasMany(Pembina::class);
    }
}