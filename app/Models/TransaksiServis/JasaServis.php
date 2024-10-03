<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaServis extends Model
{
    use HasFactory;

    protected $table = 'jasa_servis';
    protected $primaryKey = 'id_jasa';
    protected $fillable = [
        'jenis_jasa',
        'harga_jasa'
    ];
}
