<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Teknisi;
use App\Http\Requests\RegisterRequest;  // Import the custom request
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)  
    {
        // Create the Teknisi record
        $teknisi = Teknisi::create([
            'nama_teknisi' => $request->nama_teknisi,
            'nohp_teknisi' => $request->nohp_teknisi,  
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        // Log the teknisi in
        Auth::login($teknisi);

        // Trigger the registered event
        event(new Registered($teknisi));

        return redirect()->route('dashboard');  // Redirect to dashboard or any page you like
    }
}
