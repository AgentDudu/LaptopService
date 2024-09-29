<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ensure this is true to allow form submission
    }

    public function rules(): array
    {
        return [
            'nama_teknisi' => 'required|string|max:255',
            'nohp_teknisi' => 'required|string|max:15|unique:teknisi', // Validate unique phone number
            'password' => ['required', 'confirmed', Password::defaults()],
            'status' => 'required|in:Pemilik,Pegawai',
        ];
    }
}
