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
            $table->foreignId('id_service')->constrained('service', 'id_service')->onDelete('cascade');
            $table->foreignId('id_jasa')->constrained('jasa_servis', 'id_jasa')->cascadeOnDelete();
            $table->foreignId('id_sparepart')->constrained('sparepart', 'id_sparepart')->cascadeOnDelete();            
            $table->decimal('harga_transaksi_jasa_servis', 10,2);
            $table->integer('jumlah_sparepart_terpakai');
            $table->integer('jangka_garansi_bulan');
            $table->date('akhir_garansi');
            $table->decimal('subtotal_servis', 10,2);
            $table->decimal('subtotal_sparepart',10,2);
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
