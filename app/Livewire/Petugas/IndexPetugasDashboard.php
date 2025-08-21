<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class IndexPetugasDashboard extends Component
{
    public $perangkat = [];
    public $totalPerangkat = 0;
    public $totalAktif = 0;
    public $totalNonaktif = 0;
    public $isLoading = true;

    public function mount()
    {
        $this->fetchData();
        $this->fetchPerangkat();
    }

    public function fetchData()
    {
        try {
            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/sensor-monitor/connected');

            if ($response->successful()) {
                $this->perangkat = $response->json()['data']['data'];
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching data: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function fetchPerangkat()
    {
        try {
            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat');

            if ($response->successful()) {
                $data = $response->json()['data']['data'];
                $this->totalPerangkat = count($data);
                $this->totalAktif = collect($data)->where('status', 'aktif')->count();
                $this->totalNonaktif = collect($data)->where('status', 'nonaktif')->count();
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching perangkat stats: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.petugas.index-petugas-dashboard');
    }
}
