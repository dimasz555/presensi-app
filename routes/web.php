<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\LeaveRequest;
use App\Livewire\Profile;
use App\Livewire\Salary;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', Login::class)->name('login');
});


Route::middleware('auth')->group(function () {
    Route::get('/presensi', Dashboard::class)->name('presensi');
    Route::get('/pengajuan', LeaveRequest::class)->name('pengajuan');
    Route::get('/profil', Profile::class)->name('profil');
    Route::get('/slip-gaji', Salary::class)->name('slip-gaji');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
