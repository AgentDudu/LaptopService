<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sparepart\Sparepart;

class DetailTransaksiServisSparepart extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_servis_sparepart';
    public $timestamps = true;

    protected $fillable = [
        'id_service',
        'id_sparepart',
        'jumlah_sparepart_terpakai',
        'harga_per_unit',
        'subtotal_sparepart',
    ];

    public function transaksiServis()
    {
        return $this->belongsTo(TransaksiServis::class, 'id_service', 'id_service');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'id_sparepart', 'id_sparepart');
    }
}