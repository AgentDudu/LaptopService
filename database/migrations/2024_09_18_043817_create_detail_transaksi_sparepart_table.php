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
        Schema::create('detail_transaksi_sparepart', function (Blueprint $table) {
            $table->foreignId('id_transaksi_sparepart')->constrained('jual_sparepart', 'id_transaksi_sparepart');
            $table->foreignId('id_sparepart')->constrained('sparepart', 'id_sparepart');
            $table->integer('jumlah_sparepart_terjual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_sparepart');
    }
};
