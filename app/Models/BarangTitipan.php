<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BarangTitipan extends Model
{
    use HasFactory;

    protected $table = 'barang_titipan';

    protected $fillable = [
        'kode_barang',
        'kunjungan_id',
        'nama_barang',
        'deskripsi',
        'jumlah',
        'status',
        'waktu_dititipkan',
        'waktu_diserahkan',
        'waktu_diambil',
        'admin_penerima',
        'admin_penyerah',
        'catatan'
    ];

    protected $casts = [
        'waktu_dititipkan' => 'datetime',
        'waktu_diserahkan' => 'datetime',
        'waktu_diambil' => 'datetime',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function adminPenerima()
    {
        return $this->belongsTo(User::class, 'admin_penerima');
    }

    public function adminPenyerah()
    {
        return $this->belongsTo(User::class, 'admin_penyerah');
    }

    public static function generateKodeBarang()
    {
        $today = Carbon::today();
        $count = self::whereDate('waktu_dititipkan', $today)->count() + 1;
        return 'BRG' . date('dmy') . sprintf('%03d', $count);
    }
}
