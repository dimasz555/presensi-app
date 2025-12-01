<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Daftar Wajah - Sajadadir')]

class RegisterFace extends Component
{
    public $faceDescriptor;
    public $isRegistered = false;

    public function mount()
    {
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->isRegistered = auth()->user()->face_embedding !== null;
    }

    public function saveFaceDescriptor($descriptor)
    {
        try {
            $user = auth()->user();

            $user->update([
                'face_embedding' => $descriptor,
                'face_registered_at' => now(),
            ]);

            $this->isRegistered = true;
            session()->flash('success', 'Wajah Anda berhasil didaftarkan! Sekarang Anda dapat melakukan presensi.');

            return redirect()->route('profil');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan data wajah: ' . $e->getMessage());
        }
    }

    public function deleteFaceData()
    {
        try {
            $user = auth()->user();

            $user->update([
                'face_embedding' => null,
                'face_registered_at' => null,
            ]);

            $this->isRegistered = false;
            session()->flash('success', 'Data wajah berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data wajah: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.register-face');
    }
}
