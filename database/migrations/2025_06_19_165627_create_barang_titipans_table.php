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
        Schema::create('barang_titipan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->foreignId('kunjungan_id')->constrained('kunjungan');
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah')->default(1);
            $table->enum('status', ['dititipkan', 'diserahkan', 'diambil'])->default('dititipkan');
            $table->datetime('waktu_dititipkan');
            $table->datetime('waktu_diserahkan')->nullable();
            $table->datetime('waktu_diambil')->nullable();
            $table->foreignId('admin_penerima')->constrained('users');
            $table->foreignId('admin_penyerah')->nullable()->constrained('users');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_titipan');
    }
};
