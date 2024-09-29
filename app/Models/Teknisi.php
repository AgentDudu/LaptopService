<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Import the correct class
use Illuminate\Notifications\Notifiable;

class Teknisi extends Authenticatable  // Extend the Authenticatable class
{
    use Notifiable;

    protected $table = 'teknisi';  // Specify the teknisi table

    protected $fillable = [
        'nama_teknisi',
        'nohp_teknisi',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * If you have a custom identifier (like phone number), override this method
     */
    public function getAuthIdentifierName()
    {
        return 'nohp_teknisi';  // Use the phone number for authentication instead of email
    }
}