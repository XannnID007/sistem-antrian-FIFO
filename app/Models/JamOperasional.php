<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JamOperasional extends Model
{
    use HasFactory;

    protected $table = 'jam_operasional';

    protected $fillable = ['hari', 'jam_buka', 'jam_tutup', 'is_active'];

    protected $casts = [
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public static function isOperasional()
    {
        $today = strtolower(Carbon::now()->locale('id')->dayName);
        $now = Carbon::now()->format('H:i');

        $jadwal = self::where('hari', $today)
            ->where('is_active', true)
            ->first();

        if (!$jadwal) return false;

        return $now >= $jadwal->jam_buka->format('H:i') &&
            $now <= $jadwal->jam_tutup->format('H:i');
    }
}
