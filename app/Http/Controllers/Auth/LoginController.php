<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Session\TokenMismatchException;

class LoginController extends Controller
{
    public function index()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Session::has('token') && Session::has('roles')) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $response = Http::post('https://api-monitoring-banjir-production.up.railway.app/api/v1/login', [
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Pastikan roles selalu array
                $roles = $data['data']['user']['roles'];
                $rolesArray = is_array($roles) ? $roles : [$roles];

                // Simpan session
                Session::put([
                    'token' => $data['data']['token'],
                    'token_type' => $data['data']['token_type'],
                    'user'  => $data['data']['user'],
                    'roles'  => $rolesArray,
                ]);

                // Redirect berdasarkan role
                return $this->redirectBasedOnRole();
            }

            return back()
                ->withErrors(['email' => 'Login gagal. Cek email/password.'])
                ->withInput();
        } catch (TokenMismatchException $e) {
            return back()
                ->withErrors(['email' => 'Sesi telah berakhir. Silakan coba lagi.'])
                ->withInput();
        }
    }

    private function redirectBasedOnRole()
    {
        $roles = Session::get('roles', []);

        if (in_array('admin', $roles)) {
            return redirect()->route('admin.dashboard');
        } elseif (in_array('petugas', $roles)) {
            return redirect()->route('petugas.dashboard');
        } elseif (in_array('warga', $roles)) {
            return redirect()->route('warga.dashboard');
        }

        // Default redirect jika role tidak dikenali
        return redirect()->route('login')->with('error', 'Role tidak valid.');
    }
}
