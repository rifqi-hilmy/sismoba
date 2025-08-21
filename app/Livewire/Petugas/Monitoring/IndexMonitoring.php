<?php

namespace App\Livewire\Petugas\Monitoring;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class IndexMonitoring extends Component
{
    public $perangkatSensor = [];
    public $perangkatSensorConnected = [];
    public $errorMessage = null;

    public function mount()
    {
        $this->getSensorMonitoring();
        $this->getSensorMonitoringConnected();
    }

    public function getSensorMonitoring()
    {
        try {
            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/sensor-monitor');
            if ($response->successful()) {
                $data = $response->json();
                $this->perangkatSensor = $data['data'] ?? [];
            } else {
                $this->errorMessage = 'Gagal mengambil data sensor. Status: ' . $response->status();
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }

    public function getSensorMonitoringConnected()
    {
        try {
            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/sensor-monitor/connected');
            if ($response->successful()) {
                $data = $response->json();
                $this->perangkatSensorConnected = $data['data']['data'] ?? [];
            } else {
                $this->errorMessage = 'Gagal mengambil data perangkat dengan sensor. Status: ' . $response->status();
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan saat memuat sensor: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.petugas.monitoring.index-monitoring');
    }

    public function refreshData()
    {
        $this->getSensorMonitoring();
        $this->getSensorMonitoringConnected();
    }
}
