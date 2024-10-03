<?php

namespace App\Models\Pelanggan;

use App\Models\Laptop\Laptop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $fillable = [
        'nama_pelanggan',
        'nohp_pelanggan'
    ];
    public function laptop()
    {
        return $this->hasMany(Laptop::class);
    }
}
