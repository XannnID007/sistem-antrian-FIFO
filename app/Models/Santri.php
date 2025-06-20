<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';

    protected $fillable = [
        'nama',
        'nim',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'nama_wali',
        'phone_wali',
        'kamar',
        'tahun_masuk',
        'is_active'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age;
    }
}
