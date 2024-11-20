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
        Schema::create('detail_transaksi_servis', function (Blueprint $table) {
            $table->string('id_service');
            $table->foreign('id_service')->references('id_service')->on('service')->onDelete('cascade');
            $table->string('id_jasa');
            $table->foreign('id_jasa')->references('id_jasa')->on('jasa_servis')->onDelete('cascade');
            $table->string('id_sparepart')->nullable();
            $table->foreign('id_sparepart')->references('id_sparepart')->on('sparepart')->onDelete('cascade');
            $table->integer('harga_transaksi_jasa_servis');
            $table->integer('jumlah_sparepart_terpakai')->nullable();
            $table->integer('jangka_garansi_bulan');
            $table->date('akhir_garansi');
            $table->integer('subtotal_servis');
            $table->integer('subtotal_sparepart')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_servis');
    }
};
