<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('Login - Sajadadir')]

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'password.required' => 'Password harus diisi',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            // Check if user is active
            if ($user->status !== 'active') {
                Auth::logout();
                $this->addError('email', 'Akun Anda tidak aktif. Hubungi administrator.');
                return;
            }

            // Check if user has 'karyawan' role
            if (!$user->hasRole('karyawan')) {
                Auth::logout();
                $this->addError('email', 'Anda tidak memiliki akses ke aplikasi ini.');
                return;
            }

            session()->regenerate();
            return redirect()->intended('/beranda');
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
