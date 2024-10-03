<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\Teknisi;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisi = Teknisi::all();  
        return view('teknisi.index', compact('teknisi')); 
    }

    public function create()
    {
        return view('teknisi.create');
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'status' => 'required',
            'nama_teknisi' => 'required',
            'nohp_teknisi' => 'required|unique:teknisi,nohp_teknisi',
            'password' => 'required|min:8'
        ]);

        $teknisi = Teknisi::create($validation);

        if ($teknisi) {
            session()->flash('success', 'Teknisi berhasil ditambahkan.');
            return redirect()->route('teknisi.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika menambahkan Teknisi.');
            return redirect()->route('teknisi.create');
        }
    }

    public function edit($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        return view('teknisi.edit', compact('teknisi'));
    }

    public function update(Request $request, $id)
    {
        $teknisi = Teknisi::findOrFail($id);

        $validation = $request->validate([
            'status' => 'required',
            'nama_teknisi' => 'required',
            'nohp_teknisi' => 'required|unique:teknisi,nohp_teknisi,' . $id . ',id_teknisi',
            'password' => 'required|min:8'
        ]);

        $teknisi->update($validation);

        if ($teknisi) {
            session()->flash('success', 'Teknisi berhasil diupdate.');
            return redirect()->route('teknisi.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika mengupdate Teknisi.');
            return redirect()->route('teknisi.edit', $id);
        }
    }

    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $teknisi->delete();

        if ($teknisi) {
            session()->flash('success', 'Teknisi berhasil dihapus.');
            return redirect()->route('teknisi.index');
        } else {
            session()->flash('error', 'Terdapat kesalahan ketika menghapus Teknisi.');
            return redirect()->route('teknisi.index');
        }
    }
}
