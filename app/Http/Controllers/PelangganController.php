<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();  
        return view('pelanggan.index', compact('pelanggan')); 
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'nama_pelanggan' => 'required',
            'nohp_pelanggan' => 'required|unique:pelanggan,nohp_pelanggan',
        ]);

        $lastPelanggan = Pelanggan::orderBy('id_pelanggan', 'desc')->first();
        $lastId = $lastPelanggan ? intval(str_replace('PL', '', $lastPelanggan->id_pelanggan)) : 0;
        $newIdPelanggan = 'PL' . ($lastId + 1);

        $validation = array_merge($validation, ['id_pelanggan' => $newIdPelanggan]);

        $pelanggan = Pelanggan::create($validation);

        if ($pelanggan) {
            session()->flash('success', 'Pelanggan berhasil ditambahkan.');
            return redirect()->route('pelanggan.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika menambahkan Pelanggan.');
            return redirect()->route('pelanggan.create');
        }
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validation = $request->validate([
            'nama_pelanggan' => 'required',
            'nohp_pelanggan' => 'required|unique:pelanggan,nohp_pelanggan,' . $id . ',id_pelanggan',
        ]);

        $pelanggan->update($validation);

        if ($pelanggan) {
            session()->flash('success', 'Pelanggan berhasil diupdate.');
            return redirect()->route('pelanggan.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika mengupdate Pelanggan.');
            return redirect()->route('pelanggan.edit', $id);
        }
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        if ($pelanggan) {
            session()->flash('success', 'Pelanggan berhasil dihapus.');
            return redirect()->route('pelanggan.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika menghapus Pelanggan.');
            return redirect()->route('pelanggan.index');
        }
    }
}
