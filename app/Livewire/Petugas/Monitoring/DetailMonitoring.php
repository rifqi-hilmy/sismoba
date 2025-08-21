<?php

namespace App\Livewire\Petugas\Monitoring;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class DetailMonitoring extends Component
{
    public $id;
    public $namaPerangkat, $lokasi, $status, $latitude, $longitude, $lastUpdate;
    public $sensorData = [];

    public function mount($id)
    {
        $this->id = $id;
        $this->fetchSensorData();
    }

    public function fetchSensorData()
    {
        try {
            $response = Http::get("https://api-monitoring-banjir-production.up.railway.app/api/v1/sensor-monitor/$this->id");

            if ($response->successful()) {
                $data = $response->json()['data'];
                $sensorDataRaw = $data['sensor_data'] ?? [];
                $this->namaPerangkat = $data['nama_perangkat'] ?? '-';
                $this->lokasi = $data['lokasi'] ?? '-';
                $this->status = $data['status'] ?? '-';
                $this->latitude = $data['latitude'] ?? '-';
                $this->longitude = $data['longitude'] ?? '-';
                $this->lastUpdate = $data['updated_at'] ?? now();


                // Hitung perubahan ketinggian
                $processedData = [];
                $n = count($sensorDataRaw);

                for ($i = 0; $i < $n; $i++) {
                    $current = $sensorDataRaw[$i];
                    $next = $sensorDataRaw[$i + 1] ?? null;

                    $perubahan = null;
                    if ($next) {
                        $perubahan = $current['ketinggian_air'] - $next['ketinggian_air'];
                    } else {
                        $perubahan = 0;
                    }

                    $processedData[] = [
                        'tanggal' => $current['tanggal'],
                        'jam' => explode(':', $current['jam'])[0] . ':' . explode(':', $current['jam'])[1],
                        'ketinggian_air' => $current['ketinggian_air'],
                        'perubahan_ketinggian' => $perubahan,
                        'curah_hujan' => $current['curah_hujan'],
                        'debit_air' => $current['debit_air'],
                        'status_monitor' => $current['status_monitor'] ?? null,
                    ];
                }

                $this->sensorData = $processedData;
            }
        } catch (\Exception $e) {
            logger()->error('Error fetching sensor data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.petugas.monitoring.detail-monitoring');
    }
}
