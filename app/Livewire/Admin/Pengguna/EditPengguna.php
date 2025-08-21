<?php

namespace App\Livewire\Admin\Pengguna;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class EditPengguna extends Component
{
    public $id;
    public $name;
    public $username;
    public $email;
    public $password;
    public $phone;
    public $roles;
    public $alamat;

    public function mount($id)
    {
        $this->id = $id;

        // Fetch user data
        $response = Http::get("https://api-monitoring-banjir-production.up.railway.app/api/v1/users/{$id}");

        if ($response->successful()) {
            $user = $response->json()['data'];

            $this->name = $user['name'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->phone = $user['phone'];
            $this->roles = $user['roles'];
            $this->alamat = $user['address'];
        } else {
            session()->flash('api', 'Gagal mengambil data pengguna.');
        }
    }

    public function submit()
    {
        $response = Http::put("https://api-monitoring-banjir-production.up.railway.app/api/v1/users/{$this->id}", [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'roles' => $this->roles,
            'address' => $this->alamat,
        ]);

        if ($response->successful()) {
            session()->flash('success', 'Pengguna berhasil diperbarui.');
            return redirect()->route('admin.pengguna.index');
        } else {
            session()->flash('api', 'Gagal memperbarui pengguna.');
        }
    }

    public function render()
    {
        return view('livewire.admin.pengguna.edit-pengguna');
    }
}
