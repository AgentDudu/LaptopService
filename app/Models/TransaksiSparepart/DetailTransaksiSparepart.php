<?php

namespace App\Models\TransaksiSparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sparepart\Sparepart;

class DetailTransaksiSparepart extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_sparepart';
    protected $keyType = 'string';
    protected $fillable = [
        'id_transaksi_sparepart',
        'id_sparepart',
        'jumlah_sparepart_terjual'
    ];
    public function TransaksiJualSparepart()
    {
        return $this->belongsTo(TransaksiJualSparepart::class, 'id_transaksi_sparepart', 'id_transaksi_sparepart');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'id_sparepart');
    }
}
