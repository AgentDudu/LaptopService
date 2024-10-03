<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teknisi extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'teknisi';
    protected $primaryKey = 'id_teknisi';
    protected $fillable = [
        'nama_teknisi',
        'nohp_teknisi',
        'status',
        'password'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'password' => 'hashed'
    ];
}
