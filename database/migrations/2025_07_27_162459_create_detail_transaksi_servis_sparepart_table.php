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
        Schema::create('detail_transaksi_servis_sparepart', function (Blueprint $table) {
            $table->id();

            $table->string('id_service');
            $table->foreign('id_service')->references('id_service')->on('service')->onDelete('cascade');
            
            $table->string('id_sparepart');
            $table->foreign('id_sparepart')->references('id_sparepart')->on('sparepart')->onDelete('cascade');
            
            $table->integer('jumlah_sparepart_terpakai');
            $table->integer('harga_per_unit');
            $table->integer('subtotal_sparepart');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_servis_sparepart');
    }
};