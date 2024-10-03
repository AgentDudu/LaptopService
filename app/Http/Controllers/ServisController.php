<?php

namespace App\Http\Controllers;

use App\Models\TransaksiServis\Service;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    public function index()
    {
        $jasaServis = Service::all();
        return view('servis.index', compact('servis'));
    }
}
