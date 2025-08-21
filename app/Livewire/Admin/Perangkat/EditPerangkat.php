<?php

namespace App\Livewire\Admin\Perangkat;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EditPerangkat extends Component
{
    public $perangkatId;
    public $nama_perangkat;
    public $lokasi;
    public $status;
    public $tipe;
    public $latitude;
    public $longitude;
    public $perangkat_parent_id;
    public $parentDevices = [];

    public function mount($id)
    {
        $this->perangkatId = $id;
        $this->fetchPerangkat();
        $this->fetchParentDevices();
    }

    public function fetchPerangkat()
    {
        $response = Http::get("https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat/{$this->perangkatId}");
        if ($response->successful()) {
            $data = $response->json();
            $device = $data['data'] ?? [];

            $this->nama_perangkat = $device['nama_perangkat'] ?? '';
            $this->lokasi = $device['lokasi'] ?? '';
            $this->status = $device['status'] ?? '';
            $this->tipe = $device['tipe'] ?? '';
            $this->latitude = $device['latitude'] ?? '';
            $this->longitude = $device['longitude'] ?? '';
            $this->perangkat_parent_id = $device['perangkat_parent_id'] ?? '';
        } else {
            Session::flash('error', 'Gagal memuat data perangkat.');
            return redirect()->route('admin.perangkat.index');
        }
    }

    public function fetchParentDevices()
    {
        $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat');
        if ($response->successful()) {
            $data = $response->json();
            $this->parentDevices = $data['data']['data'] ?? [];
        }
    }

    public function submit()
    {
        $this->validate([
            'nama_perangkat' => 'required|string',
            'lokasi' => 'required|string',
            'status' => 'required|string',
            'tipe' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'perangkat_parent_id' => 'nullable|numeric',
        ]);

        $response = Http::put("https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat/{$this->perangkatId}", [
            'nama_perangkat' => $this->nama_perangkat,
            'lokasi' => $this->lokasi,
            'status' => $this->status,
            'tipe' => $this->tipe,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'perangkat_parent_id' => $this->perangkat_parent_id,
        ]);

        if ($response->successful()) {
            Session::flash('success', 'Perangkat berhasil diperbarui.');
            return redirect()->route('admin.perangkat.index');
        } else {
            $body = $response->json();
            $message = $body['message'] ?? 'Gagal memperbarui perangkat.';
            $this->addError('api', $message);
        }
    }

    public function render()
    {
        return view('livewire.admin.perangkat.edit-perangkat');
    }
}
