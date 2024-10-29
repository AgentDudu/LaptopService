<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pelanggan';
    protected $table = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'nohp_pelanggan'
    ];

    public function service()
    {
        return $this->hasMany(Service::class, 'id_pelanggan');
    }

    public function jualSparepart()
    {
        return $this->hasMany(JualSparepart::class, 'id_pelanggan');
    }
}
