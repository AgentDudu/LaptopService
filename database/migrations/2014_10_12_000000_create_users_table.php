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
        Schema::create('teknisi', function (Blueprint $table) {
            $table->string('id_teknisi')->primary();
            $table->string('nama_teknisi', 255);
            $table->string('nohp_teknisi', 15)->unique();
            $table->enum('status', ['Pemilik', 'Pegawai'])->default('Pegawai');
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teknisi');
    }
};
