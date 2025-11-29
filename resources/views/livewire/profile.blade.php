<div class="min-h-screen bg-custom-gray-20 pb-24">
    <div class="bg-gradient-to-br from-primary to-secondary text-white px-6 pt-6 pb-20">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div>
                    <h1 class="text-2xl font-bold">Profil</h1>
                    <p class="text-white/70 text-sm">Pengaturan Akun</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-12">

        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex flex-col items-center">
                <div class="relative mb-4">
                    <div
                        class="w-24 h-24 rounded-full overflow-hidden bg-custom-gray-20 border-4 border-white shadow-lg">
                        @if ($current_avatar_url)
                            <img src="{{ $current_avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-secondary">
                                <span class="text-3xl font-bold text-white">{{ substr($name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <button wire:click="openAvatarModal"
                        class="absolute bottom-0 right-0 w-8 h-8 bg-primary rounded-full flex items-center justify-center shadow-lg hover:bg-secondary transition-colors">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <h2 class="text-xl font-bold text-custom-gray-100 mb-1">{{ $name }}</h2>
                <p class="text-sm text-custom-gray-60 mb-1">{{ $position_name }}</p>
                <p class="text-sm text-custom-gray-50">{{ $email ?: '-' }}</p>
            </div>
        </div>

        <div class="space-y-3 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <h3 class="font-semibold text-custom-gray-100 mb-3">Informasi Akun</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-custom-gray-60">Nomor Telepon</span>
                        <span class="text-sm font-medium text-custom-gray-90">{{ $phone ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-custom-gray-60">Jenis Kelamin</span>
                        <span class="text-sm font-medium text-custom-gray-90">
                            @if ($gender === 'l')
                                Laki-laki
                            @elseif($gender === 'p')
                                Perempuan
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-custom-gray-60">Status</span>
                        <span
                            class="bg-success-secondary text-success-main text-xs font-semibold px-2 py-1 rounded-full">
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            <button wire:click="openEditModal"
                class="w-full bg-white rounded-xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-secondary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-custom-gray-100">Edit Profil</p>
                        <p class="text-xs text-custom-gray-60">Ubah nama dan nomor telepon</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-custom-gray-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <button wire:click="openPasswordModal"
                class="w-full bg-white rounded-xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-warning-secondary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning-pressed" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-custom-gray-100">Ubah Password</p>
                        <p class="text-xs text-custom-gray-60">Ganti password akun Anda</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-custom-gray-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            @if (!auth()->user()->face_embedding)
                <a href="/daftar-wajah" wire:navigate
                    class="w-full bg-white rounded-xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-info-focus rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-custom-gray-100">Daftar Wajah</p>
                            <p class="text-xs text-custom-gray-60">Untuk verifikasi presensi</p>
                        </div>
                    </div>
                    <span
                        class="bg-danger-secondary text-danger-main text-xs font-semibold px-2 py-1 rounded-full">Wajib</span>
                </a>
            @endif
        </div>

        <button wire:click="logout"
            class="w-full bg-danger-main text-white font-semibold py-4 rounded-xl shadow-lg hover:bg-danger-pressed active:scale-[0.98] transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </button>
    </div>

    @if ($showEditModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
            x-data="{ show: @entangle('showEditModal') }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            @click.self="$wire.closeEditModal()">

            <div class="bg-white rounded-t-3xl sm:rounded-2xl w-full max-w-md" x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <div
                    class="sticky top-0 bg-white border-b border-custom-gray-30 px-6 py-4 flex items-center justify-between rounded-t-3xl sm:rounded-t-2xl">
                    <h3 class="text-xl font-bold text-custom-gray-100">Edit Profil</h3>
                    <button wire:click="closeEditModal"
                        class="w-8 h-8 bg-custom-gray-20 rounded-lg flex items-center justify-center hover:bg-custom-gray-30">
                        <svg class="w-5 h-5 text-custom-gray-90" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="updateProfile" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Nama Lengkap <span class="text-danger-main">*</span>
                        </label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Email <span class="text-danger-main">*</span>
                        </label>
                        <input type="email" wire:model="email"
                            class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="contoh@email.com">
                        @error('email')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="tel" wire:model="phone"
                            class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Jenis Kelamin <span class="text-danger-main">*</span>
                        </label>
                        <div class="space-y-2">
                            <label
                                class="flex items-center gap-3 p-3 border border-custom-gray-40 rounded-xl cursor-pointer hover:bg-custom-gray-20">
                                <input type="radio" wire:model="gender" value="l"
                                    class="w-4 h-4 text-primary focus:ring-primary">
                                <span class="text-sm text-custom-gray-90">Laki-laki</span>
                            </label>
                            <label
                                class="flex items-center gap-3 p-3 border border-custom-gray-40 rounded-xl cursor-pointer hover:bg-custom-gray-20">
                                <input type="radio" wire:model="gender" value="p"
                                    class="w-4 h-4 text-primary focus:ring-primary">
                                <span class="text-sm text-custom-gray-90">Perempuan</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeEditModal"
                            class="flex-1 bg-custom-gray-30 text-custom-gray-90 font-semibold py-3 rounded-xl hover:bg-custom-gray-40">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 rounded-xl hover:shadow-lg disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateProfile">Simpan</span>
                            <span wire:loading wire:target="updateProfile">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showPasswordModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
            x-data="{ show: @entangle('showPasswordModal') }" x-show="show" @click.self="$wire.closePasswordModal()">

            <div class="bg-white rounded-t-3xl sm:rounded-2xl w-full max-w-md" x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <div
                    class="sticky top-0 bg-white border-b border-custom-gray-30 px-6 py-4 flex items-center justify-between rounded-t-3xl sm:rounded-t-2xl">
                    <h3 class="text-xl font-bold text-custom-gray-100">Ubah Password</h3>
                    <button wire:click="closePasswordModal"
                        class="w-8 h-8 bg-custom-gray-20 rounded-lg flex items-center justify-center hover:bg-custom-gray-30">
                        <svg class="w-5 h-5 text-custom-gray-90" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="updatePassword" class="p-6 space-y-4">
                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Password Saat Ini <span class="text-danger-main">*</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="current_password"
                                class="w-full px-4 py-3 pr-11 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-custom-gray-60">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Password Baru <span class="text-danger-main">*</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="new_password"
                                class="w-full px-4 py-3 pr-11 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-custom-gray-60">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                            Konfirmasi Password Baru <span class="text-danger-main">*</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation"
                                class="w-full px-4 py-3 pr-11 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-custom-gray-60">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closePasswordModal"
                            class="flex-1 bg-custom-gray-30 text-custom-gray-90 font-semibold py-3 rounded-xl hover:bg-custom-gray-40">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 rounded-xl hover:shadow-lg disabled:opacity-50">
                            <span wire:loading.remove wire:target="updatePassword">Ubah Password</span>
                            <span wire:loading wire:target="updatePassword">Mengubah...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showAvatarModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
            x-data="{ show: @entangle('showAvatarModal') }" x-show="show" @click.self="$wire.closeAvatarModal()">

            <div class="bg-white rounded-t-3xl sm:rounded-2xl w-full max-w-md" x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <div
                    class="sticky top-0 bg-white border-b border-custom-gray-30 px-6 py-4 flex items-center justify-between rounded-t-3xl sm:rounded-t-2xl">
                    <h3 class="text-xl font-bold text-custom-gray-100">Foto Profil</h3>
                    <button wire:click="closeAvatarModal"
                        class="w-8 h-8 bg-custom-gray-20 rounded-lg flex items-center justify-center hover:bg-custom-gray-30">
                        <svg class="w-5 h-5 text-custom-gray-90" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex justify-center">
                        <div
                            class="w-32 h-32 rounded-full overflow-hidden bg-custom-gray-20 border-4 border-custom-gray-30">
                            @if ($current_avatar_url)
                                <img src="{{ $current_avatar_url }}" alt="Avatar"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-secondary">
                                    <span class="text-5xl font-bold text-white">{{ substr($name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form wire:submit.prevent="updateAvatar" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-custom-gray-90 mb-2">
                                Pilih Foto Baru
                            </label>
                            <input type="file" wire:model="avatar" accept="image/*"
                                class="w-full px-4 py-3 border border-custom-gray-40 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="text-xs text-custom-gray-60 mt-1">Maksimal 2MB (JPG, PNG, GIF)</p>
                            @error('avatar')
                                <p class="text-danger-main text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($avatar)
                            <div class="bg-info-focus rounded-xl p-4">
                                <p class="text-sm text-secondary font-medium">✓ Foto siap diupload</p>
                            </div>
                        @endif

                        <div class="flex gap-3">
                            @if ($current_avatar_url)
                                <button type="button" wire:click="deleteAvatar"
                                    wire:confirm="Apakah Anda yakin ingin menghapus foto profil?"
                                    wire:loading.attr="disabled"
                                    class="flex-1 bg-danger-secondary text-danger-main font-semibold py-3 rounded-xl hover:bg-danger-main hover:text-white transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="deleteAvatar">Hapus Foto</span>
                                    <span wire:loading wire:target="deleteAvatar">Menghapus...</span>
                                </button>
                            @endif

                            <button type="submit" wire:loading.attr="disabled"
                                class="flex-1 bg-gradient-to-r from-primary to-secondary text-white font-semibold py-3 rounded-xl hover:shadow-lg disabled:opacity-50">
                                <span wire:loading.remove wire:target="updateAvatar">Upload Foto</span>
                                <span wire:loading wire:target="updateAvatar">Mengupload...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @include('components.bottom-nav')
</div>

@script
    <script>
        $wire.on('show-toast', (event) => {
            if (typeof showToast === 'function') {
                showToast(event.message, event.type);
            } else {
                console.error('showToast not found!');
                alert(event.message); // Fallback
            }
        });
    </script>
@endscript
