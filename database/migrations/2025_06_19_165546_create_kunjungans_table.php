<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_antrian');
            $table->foreignId('santri_id')->constrained('santri');
            $table->string('nama_pengunjung');
            $table->string('hubungan');
            $table->string('phone_pengunjung');
            $table->text('alamat_pengunjung');
            $table->enum('status', ['menunggu', 'dipanggil', 'berlangsung', 'selesai', 'dibatalkan'])
                ->default('menunggu');
            $table->datetime('waktu_daftar');
            $table->datetime('waktu_panggil')->nullable();
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('admin_id')->constrained('users');
            $table->timestamps();

            $table->index(['status', 'waktu_daftar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
