<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\LeaveRequest;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', Login::class)
    ->middleware('guest')
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/presensi', Dashboard::class)->name('presensi');
    Route::get('/pengajuan', LeaveRequest::class)->name('pengajuan');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
