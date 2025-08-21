<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        return view('partials.admin.monitoring.index');
    }

    public function show(Request $request, $id)
    {
        return view('partials.admin.monitoring.detail', ['id' => $id]);
    }
}
