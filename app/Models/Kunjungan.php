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

    // DO NOT USE CASTS - we'll handle manually
    protected $dates = [
        'waktu_daftar',
        'waktu_panggil',
        'waktu_mulai',
        'waktu_selesai',
        'created_at',
        'updated_at'
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

    // FORCE JAKARTA TIMEZONE FOR ALL DATETIME ACCESSORS
    public function getWaktuDaftarAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
    }

    public function getWaktuPanggilAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
    }

    public function getWaktuMulaiAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
    }

    public function getWaktuSelesaiAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
    }

    public function getCreatedAtAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
    }

    public function getUpdatedAtAttribute($value)
    {
        if (!$value) return null;
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')->setTimezone('Asia/Jakarta');
    }

    // FORCE JAKARTA TIMEZONE FOR ALL DATETIME MUTATORS
    public function setWaktuDaftarAttribute($value)
    {
        if ($value) {
            $carbon = $value instanceof Carbon ? $value : Carbon::parse($value, 'Asia/Jakarta');
            $this->attributes['waktu_daftar'] = $carbon->utc()->format('Y-m-d H:i:s');
        }
    }

    public function setWaktuPanggilAttribute($value)
    {
        if ($value) {
            $carbon = $value instanceof Carbon ? $value : Carbon::parse($value, 'Asia/Jakarta');
            $this->attributes['waktu_panggil'] = $carbon->utc()->format('Y-m-d H:i:s');
        }
    }

    public function setWaktuMulaiAttribute($value)
    {
        if ($value) {
            $carbon = $value instanceof Carbon ? $value : Carbon::parse($value, 'Asia/Jakarta');
            $this->attributes['waktu_mulai'] = $carbon->utc()->format('Y-m-d H:i:s');
        }
    }

    public function setWaktuSelesaiAttribute($value)
    {
        if ($value) {
            $carbon = $value instanceof Carbon ? $value : Carbon::parse($value, 'Asia/Jakarta');
            $this->attributes['waktu_selesai'] = $carbon->utc()->format('Y-m-d H:i:s');
        }
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
        $today = Carbon::now('Asia/Jakarta');
        $count = self::whereDate('waktu_daftar', $today->format('Y-m-d'))->count() + 1;
        return 'A' . $today->format('dmy') . sprintf('%03d', $count);
    }

    public function scopeToday($query)
    {
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        return $query->whereDate('waktu_daftar', $today);
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
