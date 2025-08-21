<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetugasLogNotifikasiController extends Controller
{
    public function index()
    {
        return view('partials.petugas.log_notifikasi.index');
    }
}
