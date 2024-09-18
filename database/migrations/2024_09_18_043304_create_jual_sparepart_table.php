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
        Schema::create('jual_sparepart', function (Blueprint $table) {
            $table->id('id_transaksi_sparepart');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->foreignId('id_teknisi')->constrained('teknisi', 'id_teknisi')->onDelete('cascade');
            $table->date('tanggal_jual');
            $table->decimal('harga_total_transaksi_sparepart',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jual_sparepart');
    }
};
