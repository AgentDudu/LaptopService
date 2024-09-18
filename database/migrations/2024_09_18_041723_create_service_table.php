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
            $table->id('id_service');
            $table->foreignId('id_laptop')->constrained('laptop', 'id_laptop')->onDelete('cascade');
            $table->foreignId('id_teknisi')->constrained('teknisi', 'id_teknisi')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar');
            $table->string('status_bayar',50);
            $table->decimal('harga_total_transaksi_servis', 10,2);
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
