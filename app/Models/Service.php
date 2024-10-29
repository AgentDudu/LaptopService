<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    
    protected $fillable = [
        'id_laptop',
        'id_teknisi',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_bayar',
        'harga_total_transaksi_servis'
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class, 'id_laptop');
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    public function detailTransaksiServis()
    {
        return $this->hasMany(DetailTransaksiServis::class, 'id_service');
    }
}
