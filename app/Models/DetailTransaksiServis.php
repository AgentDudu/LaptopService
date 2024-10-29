<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiServis extends Model
{
    
    protected $table = detail_transaksi_servis;

    protected $primaryKey = id_servis;

    protected $fillable =[
        'id_service',
        'id_jasa',
        'id_sparepart',
        'harga_transaksi_jasa_servis',
        'jumlah_sparepart_terpakai',
        'jangka_garansi_bulan',
        'akhir_garanasi',
        'subtotal_servis',
        'subtotal_sparepart'
    ];
}
