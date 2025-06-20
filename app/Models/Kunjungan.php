<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'nomor_antrian',
        'santri_id',
        'nama_pengunjung',
        'hubungan',
        'phone_pengunjung',
        'alamat_pengunjung',
        'status',
        'waktu_daftar',
        'waktu_panggil',
        'waktu_mulai',
        'waktu_selesai',
        'catatan',
        'admin_id'
    ];

    protected $casts = [
        'waktu_daftar' => 'datetime',
        'waktu_panggil' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function barangTitipan()
    {
        return $this->hasMany(BarangTitipan::class);
    }

    public function getDurasiKunjunganAttribute()
    {
        if ($this->waktu_mulai && $this->waktu_selesai) {
            return $this->waktu_mulai->diffInMinutes($this->waktu_selesai);
        }
        return null;
    }

    public function getWaktuTungguAttribute()
    {
        if ($this->waktu_daftar && $this->waktu_panggil) {
            return $this->waktu_daftar->diffInMinutes($this->waktu_panggil);
        }
        return null;
    }

    public static function generateNomorAntrian()
    {
        $today = Carbon::today();
        $count = self::whereDate('waktu_daftar', $today)->count() + 1;
        return 'A' . date('dmy') . sprintf('%03d', $count);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('waktu_daftar', Carbon::today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFifoOrder($query)
    {
        return $query->orderBy('waktu_daftar', 'asc');
    }
}
