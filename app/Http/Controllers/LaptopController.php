<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan\Pelanggan;
use App\Models\Laptop\Laptop;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index()
    {
        $laptop = Laptop::with('pelanggan')->get();
        $pelanggan = Pelanggan::all();  
        return view('laptop.index', compact('laptop','pelanggan')); 
    }

    public function create()
    {
        return view('laptop.create');
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'merek_laptop' => 'required',
            'deskripsi_masalah' => 'required'
        ]);

        $laptop = Laptop::create($validation);
        if($laptop) {
            session()->flash('success', 'Laptop berhasil ditambahkan.');
            return redirect()->route('laptop.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika menambahkan Laptop.');
            return redirect()->route('laptop.index');
        }
    }

    public function edit($id)
    {
        $laptop = Laptop::findOrFail($id);
        $pelanggan = Pelanggan::all();
        return view('laptop.edit', compact('laptop', 'pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'merek_laptop' => 'required',
            'deskripsi_masalah' => 'required'
        ]);

        $laptop = Laptop::findOrFail($id);
        $laptop->update($request->all());

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil diupdate.');
    }

    public function destroy($id)
    {
        $laptop = Laptop::findOrFail($id);
        $laptop->delete();

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil dihapus.');
    }
}
