<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WargaMonitoringController extends Controller
{
    public function index()
    {
        return view('partials.warga.monitoring.index');
    }

    public function show(Request $request, $id)
    {
        return view('partials.warga.monitoring.detail', ['id' => $id]);
    }
}
