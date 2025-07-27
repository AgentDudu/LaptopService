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
            $table->id(); // BARU: Menambahkan primary key standar

            $table->string('id_service');
            $table->foreign('id_service')->references('id_service')->on('service')->onDelete('cascade');
            
            $table->string('id_jasa');
            $table->foreign('id_jasa')->references('id_jasa')->on('jasa_servis')->onDelete('cascade');
            
            $table->integer('harga_transaksi_jasa_servis');
            
            // Kolom garansi per jasa
            $table->integer('jangka_garansi_bulan')->default(0);
            $table->date('akhir_garansi')->nullable();
            
            $table->timestamps();

            // HAPUS: Semua kolom terkait sparepart dan subtotal dihapus dari sini.
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