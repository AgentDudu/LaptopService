<?php

namespace App\Models\Sparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'sparepart';
    protected $primaryKey = 'id_sparepart';
    protected $fillable = [
        'jenis_sparepart',
        'merek_sparepart',
        'model_sparepart',
        'harga_sparepart'
    ];
}
