<?php

namespace App\Livewire\Admin\Pengguna;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CreatePengguna extends Component
{
    public $name;
    public $email;
    public $username;
    public $password;
    public $phone;
    public $roles;
    public $alamat;

    public $successMessage;
    public $errorMessage;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string',
            'phone' => 'required|string',
            'roles' => 'required|in:admin,petugas,warga',
            'alamat' => 'required|string',
        ]);

        try {
            $response = Http::post('https://api-monitoring-banjir-production.up.railway.app/api/v1/users', [
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'password' => $this->password,
                'phone' => $this->phone,
                'roles' => $this->roles,
                'address' => $this->alamat,
            ]);

            if ($response->successful()) {
                Session::flash('success', 'Pengguna berhasil dibuat.');
                return redirect()->route('admin.pengguna.index');
            } else {
                $body = $response->json();

                if (isset($body['data']) && is_array($body['data'])) {
                    foreach ($body['data'] as $field => $messages) {
                        foreach ($messages as $message) {
                            $this->addError($field, $message);
                        }
                    }
                } else {
                    $this->addError('api', $body['message'] ?? 'Gagal menyimpan pengguna.');
                }
            }
        } catch (\Exception $e) {
            $this->addError('api', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pengguna.create-pengguna');
    }
}
