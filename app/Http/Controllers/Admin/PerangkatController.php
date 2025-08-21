<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PerangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('partials.admin.perangkat.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partials.admin.perangkat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('partials.admin.perangkat.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $response = Http::delete("https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat/{$id}");

            if ($response->successful()) {
                return redirect()->route('admin.perangkat.index')->with('success', 'Perangkat berhasil dihapus.');
            }

            return redirect()->back()->with('error', 'Gagal menghapus perangkat: ' . $response->json()['message'] ?? '');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
