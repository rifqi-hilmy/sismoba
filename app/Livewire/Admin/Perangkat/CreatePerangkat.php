<?php

namespace App\Livewire\Admin\Perangkat;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CreatePerangkat extends Component
{
    public $nama_perangkat;
    public $lokasi;
    public $status;
    public $tipe;
    public $latitude;
    public $longitude;
    public $perangkat_parent_id;
    public $parentDevices = [];
    public $successMessage;
    public $errorMessage;

    public function mount()
    {
        $this->fetchParentDevices();
    }

    public function fetchParentDevices()
    {
        try {
            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat');

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['data'])) {
                    $this->parentDevices = $data['data']['data'];
                }
            }
        } catch (\Exception $e) {
            $this->addError('api', 'Gagal mengambil perangkat parent: ' . $e->getMessage());
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

        try {
            $response = Http::post('https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat', [
                'nama_perangkat' => $this->nama_perangkat,
                'lokasi' => $this->lokasi,
                'status' => $this->status,
                'tipe' => $this->tipe,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'perangkat_parent_id' => $this->perangkat_parent_id,
            ]);

            if ($response->successful()) {
                Session::flash('success', 'Perangkat berhasil dibuat.');
                return redirect()->route('admin.perangkat.index');
            } else {
                $body = $response->json();

                if (isset($body['data']) && is_array($body['data'])) {
                    foreach ($body['data'] as $field => $messages) {
                        foreach ($messages as $message) {
                            $this->addError($field, $message);
                        }
                    }
                } else {
                    $this->addError('api', $body['message'] ?? 'Gagal menyimpan perangkat.');
                }
            }
        } catch (\Exception $e) {
            $this->addError('api', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.perangkat.create-perangkat');
    }
}
