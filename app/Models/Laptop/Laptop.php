<?php

namespace App\Models\Laptop;

use App\Models\Pelanggan\Pelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    use HasFactory;

    protected $table = 'laptop';
    protected $primaryKey = 'id_laptop';
    protected $fillable = [
        'id_pelanggan',
        'merek_laptop',
        'deskripsi_masalah'
    ];
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
