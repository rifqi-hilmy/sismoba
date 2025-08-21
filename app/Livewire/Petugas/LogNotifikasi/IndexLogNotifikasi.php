<?php

namespace App\Livewire\Petugas\LogNotifikasi;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class IndexLogNotifikasi extends Component
{
    public $logNotifikasi = [];
    public $lokasiList = [];
    public $currentPage = 1;
    public $lastPage = 1;
    public $total = 0;
    public $perPage = 10;
    public $search = '';
    public $selectedLokasi = '';
    public $errorMessage = null;

    public function mount()
    {
        $this->getLogNotifikasi();
        $this->getLokasiList();
    }

    public function getLokasiList()
    {
        try {
            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat');
            if ($response->successful()) {
                $data = $response->json();
                $perangkatData = $data['data']['data'] ?? [];

                // Ambil lokasi unik
                $this->lokasiList = collect($perangkatData)
                    ->pluck('lokasi')
                    ->unique()
                    ->sort()
                    ->values()
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Optional: log atau tampilkan error
            $this->lokasiList = [];
        }
    }

    public function getLogNotifikasi()
    {
        try {
            $params = ['page' => $this->currentPage];

            if (!empty($this->search)) {
                $params['search'] = $this->search;
            }

            if (!empty($this->selectedLokasi)) {
                $params['lokasi'] = $this->selectedLokasi;
            }

            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/log-notifikasi', $params);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['data'])) {
                    $this->logNotifikasi = $data['data']['data'];
                    $this->currentPage = $data['data']['current_page'];
                    $this->lastPage = $data['data']['last_page'];
                    $this->perPage = $data['data']['per_page'];
                    $this->total = $data['data']['total'];
                    $this->errorMessage = null;
                } else {
                    $this->errorMessage = "Struktur data tidak valid dari API.";
                }
            } else {
                $this->errorMessage = "Gagal mengambil data. Status: " . $response->status();
            }
        } catch (\Exception $e) {
            $this->errorMessage = "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    public function updatedSearch()
    {
        $this->currentPage = 1;
        $this->getLogNotifikasi();
    }

    public function updatedselectedLokasi()
    {
        $this->currentPage = 1;
        $this->getLogNotifikasi();
    }

    // Modifikasi method goToPage:
    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->lastPage) {
            $this->currentPage = $page;
            $this->getLogNotifikasi();
        }
    }

    public function render()
    {
        return view('livewire.petugas.log-notifikasi.index-log-notifikasi');
    }

    public function refreshData()
    {
        $this->getLogNotifikasi();
    }
}
