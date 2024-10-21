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
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($laptop) {
            $latestLaptop = static::latest('id_laptop')->first();

            if (!$latestLaptop) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('LP', '', $latestLaptop->id_laptop);
                $nextIdNumber = $lastId + 1;
            }

            $laptop->id_laptop = 'LP' . $nextIdNumber;
        });
    }
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
