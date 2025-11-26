<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Profil - Sajadadir')]

class Profile extends Component
{
    use WithFileUploads;

    // Profile Section
    public $name;
    public $email;
    public $phone;
    public $gender;
    public $position_name;
    public $avatar;
    public $current_avatar_url;

    // Password Section
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    // UI State
    public $showEditModal = false;
    public $showPasswordModal = false;
    public $showAvatarModal = false;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
        'avatar' => 'nullable|image|max:2048', // 2MB max
    ];

    protected $messages = [
        'name.required' => 'Nama harus diisi',
        'name.min' => 'Nama minimal 3 karakter',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan oleh user lain',
        'phone.regex' => 'Format nomor telepon tidak valid',
        'phone.min' => 'Nomor telepon minimal 10 digit',
        'phone.unique' => 'Nomor telepon sudah digunakan oleh user lain',
        'avatar.image' => 'File harus berupa gambar',
        'avatar.max' => 'Ukuran gambar maksimal 2MB',
    ];

    public function mount()
    {
        // Check if user has karyawan role
        if (!auth()->user()->hasRole('karyawan')) {
            abort(403, 'Unauthorized');
        }

        $this->loadUserData();
    }

    public function loadUserData()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->gender = $user->gender;
        $this->position_name = $user->position?->name ?? 'Staff';

        // Get avatar URL from Spatie Media Library
        $this->current_avatar_url = $user->getFirstMediaUrl('avatar') ?: null;
    }

    public function openEditModal()
    {
        $this->showEditModal = true;
        $this->resetValidation();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->loadUserData();
        $this->resetValidation();
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|min:3|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . auth()->id(),
            ],
            'phone' => [
                'nullable',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:15',
                'unique:users,phone,' . auth()->id(),
            ],
            'gender' => 'required|in:l,p',
        ], [
            'gender.required' => 'Jenis kelamin harus dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
        ]);

        $this->loadUserData();
        $this->closeEditModal();
        session()->flash('success', 'Profil berhasil diperbarui!');
    }

    public function openPasswordModal()
    {
        $this->showPasswordModal = true;
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->resetValidation();
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->resetValidation();
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'new_password.min' => 'Password minimal 6 karakter',
        ]);

        $user = auth()->user();

        // Check current password
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah');
            return;
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->closePasswordModal();
        session()->flash('success', 'Password berhasil diperbarui!');
    }

    public function openAvatarModal()
    {
        $this->showAvatarModal = true;
        $this->avatar = null;
        $this->resetValidation();
    }

    public function closeAvatarModal()
    {
        $this->showAvatarModal = false;
        $this->avatar = null;
        $this->resetValidation();
    }

    public function updateAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:2048',
        ], [
            'avatar.required' => 'Pilih foto terlebih dahulu',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $user = auth()->user();

        // Delete old avatar
        $user->clearMediaCollection('avatar');

        // Add new avatar
        $user->addMedia($this->avatar->getRealPath())
            ->usingFileName($this->avatar->getClientOriginalName())
            ->toMediaCollection('avatar');

        $this->loadUserData();
        $this->closeAvatarModal();
        session()->flash('success', 'Foto profil berhasil diperbarui!');
    }

    public function deleteAvatar()
    {
        $user = auth()->user();
        $user->clearMediaCollection('avatar');

        $this->loadUserData();
        $this->closeAvatarModal();
        session()->flash('success', 'Foto profil berhasil dihapus!');
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
