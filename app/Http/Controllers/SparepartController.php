<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SparepartController extends Controller
{
    //
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;


class SparepartController extends Controller
{
    public function index()
    {
        $sparepart = Sparepart::all();
        return view('sparepart.index', compact('sparepart'));
    }

    public function create()
    {
        return view('sparepart.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'harga_sparepart' => 'required',
            'merk_sparepart' => 'required',
            'jenis_sparepart' => 'required',
            'model_sparepart' => 'required',
        ]);

        Sparepart::create($validated);

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan');
    }

    public function edit(Sparepart $sparepart)
    {
        return view('sparepart.edit', compact('sparepart'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'harga_sparepart' => 'required',
            'merk_sparepart' => 'required',
            'jenis_sparepart' => 'required',
            'model_sparepart' => 'required',
        ]);

        $sparepart->update($validated);

        return redirect()->route('sparepart.index')->with('success', 'Sparepart behasil diupdate');
    }

    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();

        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil dihapus');
    }
}
