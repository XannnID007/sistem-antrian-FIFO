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
        'is_active' => 'boolean',
    ];

    // Remove datetime casting for jam_buka and jam_tutup to handle as time only
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function isOperasional()
    {
        // Get current time in Jakarta timezone
        $now = Carbon::now('Asia/Jakarta');
        $today = strtolower($now->locale('id')->dayName);
        $currentTime = $now->format('H:i');

        $jadwal = self::where('hari', $today)
            ->where('is_active', true)
            ->first();

        if (!$jadwal) return false;

        return $currentTime >= $jadwal->jam_buka && $currentTime <= $jadwal->jam_tutup;
    }

    // Accessor untuk jam_buka
    public function getJamBukaAttribute($value)
    {
        return $value ? substr($value, 0, 5) : null; // Return HH:MM format
    }

    // Accessor untuk jam_tutup
    public function getJamTutupAttribute($value)
    {
        return $value ? substr($value, 0, 5) : null; // Return HH:MM format
    }
}
