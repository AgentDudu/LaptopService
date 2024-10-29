<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JualSparepart extends Model
{
    use HasFactory;

    protected $table = 'jual_sparepart';

    protected $primaryKey = 'id_transaksi_sparepart';

    protected $fillable = [
        'id_transaksi_sparepart', 
        'id_pelanggan',
        'id_teknisi',
        'tanggal_jual',
        'harga_total_transaksi_sparepart',
    ];

    // Relationship to Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    // Relationship to Teknisi
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    // Relationship to DetailTransaksiSparepart 
    public function detail_transaksi_sparepart()
    {
        return $this->hasMany(DetailTransaksiSparepart::class, 'id_transaksi_sparepart');
    }
    
}
