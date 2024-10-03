<?php

namespace App\Http\Controllers;

use App\Models\TransaksiServis\JasaServis;
use Illuminate\Http\Request;

class JasaServisController extends Controller
{
    public function index()
    {
        $jasaServis = JasaServis::all();
        return view('jasaServis.index', compact('jasaServis'));
    }

    public function create()
    {
        return view('jasaServis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric',
        ]);

        JasaServis::create($validated);

        return redirect()->route('jasaServis.index')->with('success', 'Jasa servis berhasil ditambahkan.');
    }

    public function show($id)
    {
        $jasaServis = JasaServis::findOrFail($id);
        return view('jasaServis.show', compact('jasaServis'));
    }

    public function edit($id)
    {
        $jasaServis = JasaServis::findOrFail($id);
        return view('jasaServis.edit', compact('jasaServis'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric',
        ]);

        $jasaServis = JasaServis::findOrFail($id);
        $jasaServis->update($validated);

        return redirect()->route('jasaServis.index')->with('success', 'Jasa servis berhasil diupdate.');
    }

    public function destroy($id)
    {
        $jasaServis = JasaServis::findOrFail($id);
        $jasaServis->delete();

        return redirect()->route('jasaServis.index')->with('success', 'Jasa servis berhasil dihapus.');
    }
}
