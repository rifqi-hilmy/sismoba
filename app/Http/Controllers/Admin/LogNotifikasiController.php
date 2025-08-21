<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogNotifikasiController extends Controller
{
    public function index()
    {
        return view('partials.admin.log_notifikasi.index');
    }
}
