<?php

use App\Livewire\Absensi;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\LeaveRequest;
use App\Livewire\PayrollHistory;
use App\Livewire\Profile;
use App\Livewire\RegisterFace;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', Login::class)->name('login');
});


Route::middleware('auth')->group(function () {
    Route::get('/presensi', Dashboard::class)->name('presensi');
    Route::get('/absensi', Absensi::class)->name('absensi');
    Route::get('/daftar-wajah', RegisterFace::class)->name('register-face');
    Route::get('/pengajuan', LeaveRequest::class)->name('pengajuan');
    Route::get('/profil', Profile::class)->name('profil');
    Route::get('/slip-gaji', PayrollHistory::class)->name('slip-gaji');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
