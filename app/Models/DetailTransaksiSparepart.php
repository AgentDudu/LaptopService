<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiSparepart extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_sparepart';

    protected $primaryKey = 'id_transaksi_sparepart';
    protected $fillable = [
        'id_sparepart',
        'jumlah_sparepart_terjual',
        'model_sparepart',
        'jenis_sparepart',
        'merek_sparepart',
        'harga_sparepart'
    ];

    // Relationship to JualSparepart
    public function jualSparepart() 
    {
        return $this->belongsTo(JualSparepart::class, 'id_transaksi_sparepart');
    }

    // Relationship to Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'id_sparepart');
    }
}
