<?php

namespace App\Livewire\Admin\Pengguna;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

class IndexPengguna extends Component
{
    public $users = [];
    public $currentPage = 1;
    public $lastPage = 1;
    public $total = 0;
    public $perPage = 10;
    public $search = '';
    public $selectedRole = '';
    public $errorMessage = null;

    public function mount()
    {
        $this->getUsers();
    }

    public function getUsers()
    {
        try {
            $params = ['page' => $this->currentPage];

            if (!empty($this->search)) {
                $params['search'] = $this->search;
            }

            if (!empty($this->selectedRole)) {
                $params['roles'] = $this->selectedRole;
            }

            $response = Http::get('https://api-monitoring-banjir-production.up.railway.app/api/v1/users', $params);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']['data'])) {
                    $this->users = $data['data']['data'];
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
        $this->getUsers();
    }

    public function updatedSelectedRole()
    {
        $this->currentPage = 1;
        $this->getUsers();
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
            $this->getUsers();
            $this->dispatch('post-update'); // <<< PENTING!
        }
    }

    public function render()
    {
        return view('livewire.admin.pengguna.index-pengguna');
    }

    // Refresh data secara realtime
    public function refreshData()
    {
        $this->getUsers();
    }
}
