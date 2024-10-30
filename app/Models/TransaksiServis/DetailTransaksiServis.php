<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sparepart\Sparepart;


class DetailTransaksiServis extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_servis';
    protected $keyType = 'string';
    protected $fillable = [
        'id_service',
        'id_jasa',
        'id_sparepart',
        'harga_transaksi_jasa_servis',
        'jumlah_sparepart_terpakai',
        'jangka_garansi_bulan',
        'akhir_garansi',
        'subtotal_servis',
        'subtotal_sparepart'
    ];
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }
    public function jasaServis()
    {
        return $this->belongsTo(JasaServis::class, 'id_jasa', 'id_jasa');
    }
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'id_sparepart', 'id_sparepart');
    }

}
