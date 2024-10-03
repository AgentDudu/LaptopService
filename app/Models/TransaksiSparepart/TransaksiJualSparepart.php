<?php

namespace App\Models\TransaksiSparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiJualSparepart extends Model
{
    use HasFactory;

    protected $table = 'jual_sparepart';
    protected $primaryKey = 'id_transaksi_sparepart';
    protected $fillable = [
        'id_pelanggan',
        'id_teknisi',
        'tanggal_jual',
        'harga_total_transaksi_sparepart'
    ];
}
