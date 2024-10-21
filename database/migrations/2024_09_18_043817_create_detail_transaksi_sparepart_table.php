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
            $table->string('id_transaksi_sparepart');
            $table->foreign('id_transaksi_sparepart')->references('id_transaksi_sparepart')->on('jual_sparepart')->onDelete('cascade');
            $table->string('id_sparepart');
            $table->foreign('id_sparepart')->references('id_sparepart')->on('sparepart')->onDelete('cascade');
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
