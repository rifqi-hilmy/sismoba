<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetugasMonitoringController extends Controller
{
    public function index()
    {
        return view('partials.petugas.monitoring.index');
    }

    public function show(Request $request, $id)
    {
        return view('partials.petugas.monitoring.detail', ['id' => $id]);
    }
}
