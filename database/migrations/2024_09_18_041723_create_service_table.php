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
        Schema::create('service', function (Blueprint $table) {
            $table->string('id_service')->primary();  // Hanya satu primary key
            $table->string('id_laptop');
            $table->foreign('id_laptop')->references('id_laptop')->on('laptop')->onDelete('cascade');
            $table->string('id_teknisi');
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar');
            $table->string('status_bayar', 50);
            $table->integer('harga_total_transaksi_servis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
