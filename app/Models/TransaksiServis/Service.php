<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'id_service';
    protected $fillable = [
        'id_laptop',
        'id_teknisi',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_bayar',
        'harga_total_transaksi_servis'
    ];
    protected $dates = [
        'tanggal_masuk',
    ];
    protected $casts = [
        'id_service' => 'integer',
        'tanggal_masuk' => 'datetime',
    ];
}
