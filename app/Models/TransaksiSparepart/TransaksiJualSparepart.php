<?php

namespace App\Models\TransaksiSparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiJualSparepart extends Model
{
    use HasFactory;

    protected $table = 'jual_sparepart';
    protected $primaryKey = 'id_transaksi_sparepart';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksiSparepart) {
            $latestTransaksiSP = static::latest('id_transaksi_sparepart')->first();

            if (!$latestTransaksiSP) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('TSP', '', $latestTransaksiSP->id_transaksi_sparepart);
                $nextIdNumber = $lastId + 1;
            }

            $transaksiSparepart->id_transaksi_sparepart = 'TSP' . $nextIdNumber;
        });
    }
    protected $fillable = [
        'id_pelanggan',
        'id_teknisi',
        'tanggal_jual',
        'harga_total_transaksi_sparepart'
    ];
}
