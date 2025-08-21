<?php

namespace App\Livewire\Admin\Perangkat;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

class IndexPerangkat extends Component
{
    public $perangkat = [];
    public $currentPage = 1;
    public $lastPage = 1;
    public $total = 0;
    public $perPage = 10;
    public $search = '';
    public $selectedStatus = '';
    public $selectedTipe = '';
    public $errorMessage = null;

    public function mount()
    {
        $this->getPerangkat();
    }

    public function getPerangkat()
    {
        try {
            $params = ['page' => $this->currentPage];

            if (!empty($this->search)) {
                $params['search'] = $this->search;
            }

            if (!empty($this->selectedStatus)) {
                $params['status'] = $this->selectedStatus;
            }

            if (!empty($this->selectedTipe)) {
                $params['tipe'] = $this->selectedTipe;
            }

            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/perangkat', $params);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['data'])) {
                    $this->perangkat = $data['data']['data'];
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
        $this->getPerangkat();
    }

    public function updatedselectedStatus()
    {
        $this->currentPage = 1;
        $this->getPerangkat();
    }

    public function updatedselectedTipe()
    {
        $this->currentPage = 1;
        $this->getPerangkat();
    }

    #[On('post-update')]
    public function reinitFlowbite()
    {
        // Hanya pemicu untuk menjalankan JS setelah update
    }

    // Modifikasi method goToPage:
    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->lastPage) {
            $this->currentPage = $page;
            $this->getPerangkat();
            $this->dispatch('post-update'); // <<< PENTING!
        }
    }

    public function render()
    {
        return view('livewire.admin.perangkat.index-perangkat');
    }

    public function refreshData()
    {
        $this->getPerangkat();
    }
}
