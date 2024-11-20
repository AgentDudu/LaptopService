<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Teknisi;
use App\Http\Requests\RegisterRequest;  
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }
    public function store(RegisterRequest $request)  
    {
        $teknisi = Teknisi::create([
            'nama_teknisi' => $request->nama_teknisi,
            'nohp_teknisi' => $request->nohp_teknisi,  
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);
        event(new Registered($teknisi));
        return redirect()->route('home');  
    }
}
