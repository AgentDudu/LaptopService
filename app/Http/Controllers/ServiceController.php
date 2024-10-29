<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Laptop;
use App\Models\Teknisi;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // List all services
    public function index()
    {
        $services = Service::with(['laptop', 'teknisi'])->get();
        return view('service.index', compact('services'));
    }

    // Show the form to create a new service
    public function create()
    {
        $laptops = Laptop::all();
        $teknisis = Teknisi::all();
        return view('service.create', compact('laptops', 'teknisis'));
    }

    // Store a new service
    public function store(Request $request)
    {
        $request->validate([
            'id_laptop' => 'required|exists:laptop,id_laptop',
            'id_teknisi' => 'required|exists:teknisi,id_teknisi',
            'tanggal_masuk' => 'required|date',
            'status_bayar' => 'required|string|max:50',
            'harga_total_transaksi_servis' => 'required|numeric'
        ]);

        $service = new Service([
            'id_laptop' => $request->get('id_laptop'),
            'id_teknisi' => $request->get('id_teknisi'),
            'tanggal_masuk' => $request->get('tanggal_masuk'),
            'tanggal_keluar' => $request->get('tanggal_keluar'),
            'status_bayar' => $request->get('status_bayar'),
            'harga_total_transaksi_servis' => $request->get('harga_total_transaksi_servis')
        ]);

        $service->save();

        return redirect()->route('service.index')->with('success', 'Service created successfully');
    }

    // Show a specific service
    public function show($id)
    {
        $service = Service::with(['laptop', 'teknisi', 'detailTransaksiServis'])->findOrFail($id);
        return view('service.show', compact('service'));
    }

    // Show the form to edit an existing service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $laptops = Laptop::all();
        $teknisis = Teknisi::all();
        return view('service.edit', compact('service', 'laptops', 'teknisis'));
    }

    // Update an existing service
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_laptop' => 'required|exists:laptop,id_laptop',
            'id_teknisi' => 'required|exists:teknisi,id_teknisi',
            'tanggal_masuk' => 'required|date',
            'status_bayar' => 'required|string|max:50',
            'harga_total_transaksi_servis' => 'required|numeric'
        ]);

        $service = Service::findOrFail($id);
        $service->id_laptop = $request->get('id_laptop');
        $service->id_teknisi = $request->get('id_teknisi');
        $service->tanggal_masuk = $request->get('tanggal_masuk');
        $service->tanggal_keluar = $request->get('tanggal_keluar');
        $service->status_bayar = $request->get('status_bayar');
        $service->harga_total_transaksi_servis = $request->get('harga_total_transaksi_servis');

        $service->save();

        return redirect()->route('service.index')->with('success', 'Service updated successfully');
    }

    // Delete a service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Service deleted successfully');
    }
}
