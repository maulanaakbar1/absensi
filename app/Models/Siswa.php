<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'ekstrakurikuler_id',
        'nis',
        'nisn',
        'kelas',
        'tahun_angkatan',
        'jenis_kelamin',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ayah',
        'nama_ibu',
        'no_telp_ayah',
        'no_telp_ibu'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ekstrakurikuler() {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function absensis() {
        return $this->hasMany(Absensi::class);
    }
}
